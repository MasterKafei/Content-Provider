<?php

namespace App\Command;

use App\Business\IgdbBusiness;
use App\Business\Model\IgdbRequest;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\Service\Attribute\Required;

#[AsCommand(name: 'app:test')]
class TestCommand extends Command
{
    private IgdbBusiness $igdbBusiness;

    #[Required]
    public function setIgdbBusiness(IgdbBusiness $igdbBusiness): void
    {
        $this->igdbBusiness = $igdbBusiness;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $request = new IgdbRequest();
        $request
            ->setRouteName('igdb_genres')
            ->addField('name')
            ->setLimit(100)
            ->setOffset(0)
        ;

        $results = $this->igdbBusiness->request($request);
        $formattedResults = array_map(function(array $result) {
            return $result['id'] . " " . $result['name'];
        }, $results);

        dump($formattedResults);
        return Command::SUCCESS;
    }
}
