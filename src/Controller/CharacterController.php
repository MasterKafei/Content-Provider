<?php

namespace App\Controller;

use App\Business\IgdbBusiness;
use App\Business\Model\IgdbRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CharacterController extends AbstractController
{
    #[Route(path: '/characters', name: 'app_character_list')]
    public function list(IgdbBusiness $igdbBusiness): Response
    {
        $igdbRequest = (new IgdbRequest())
            ->setRouteName('igdb_characters')
            ->addField('*')
            ->addField('games.*')
        ;

        dd($igdbBusiness->request($igdbRequest));

        return $this->render('Page/Character/list.html.twig', [
            ''
        ]);
    }
}
