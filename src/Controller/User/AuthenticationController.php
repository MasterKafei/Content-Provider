<?php

namespace App\Controller\User;

use App\Form\Type\User\Authentication\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user/authentication')]
class AuthenticationController extends AbstractController
{
    #[Route('/login', name: 'app_user_authentication_login')]
    public function login(): Response
    {
        $form = $this->createForm(LoginType::class);

        return $this->render('Page/User/Authentication/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/logout', name: 'app_user_authentication_logout')]
    public function logout(): void
    {
        throw new \RuntimeException('Should never be called');
    }
}
