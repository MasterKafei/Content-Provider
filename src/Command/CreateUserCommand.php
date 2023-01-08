<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\Service\Attribute\Required;

#[AsCommand(
    name: 'app:create:user',
    description: 'Create a user for this application',
)]
class CreateUserCommand extends Command
{

    private InputInterface $input;

    private OutputInterface $output;

    private UserRepository $userRepository;

    #[Required]
    public function setUserRepository(UserRepository $userRepository): void
    {
        $this->userRepository = $userRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;
        $io = new SymfonyStyle($input, $output);

        $fields = [
            'username' => false,
            'plainPassword' => true,
        ];

        $user = new User();
        $reflectionClass = new \ReflectionClass($user);
        foreach ($fields as $field => $isHidden) {
            $value = $this->ask($field, $isHidden);
            $propertyClass = $reflectionClass->getProperty($field);
            $propertyClass->setAccessible(true);
            $propertyClass->setValue($user, $value);
        }

        if(null !== $this->userRepository->findOneBy(['username' => $user->getUsername()])) {
            $io->caution("Can't create user with '{$user->getUsername()}' username', user already exists");
            return Command::FAILURE;
        }

        $this->userRepository->save($user, true);

        $io->success("New user created : {$user->getUsername()}");

        return Command::SUCCESS;
    }

    public function ask(string $name, bool $hide = false): string
    {
        $name = ucfirst($name);
        $questionHelper = new QuestionHelper();
        $question = new Question("$name: ");
        $question->setHidden($hide);
        return (string)$questionHelper->ask($this->input, $this->output, $question);
    }
}
