<?php

namespace App\Listener\Doctrine;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserListener
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function prePersist(User $user): void
    {
        if (null === $user->getPassword() && $user->getPlainPassword() !== null) {
            $user->setPassword($this->hasher->hashPassword($user, $user->getPlainPassword()));
        }
    }
}
