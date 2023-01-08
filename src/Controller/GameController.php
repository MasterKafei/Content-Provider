<?php

namespace App\Controller;

use App\Business\IgdbBusiness;
use App\Business\Model\IgdbRequest;
use App\Form\Model\Video\AddGameToVideoModel;
use App\Form\Type\Game\SearchGameType;
use App\Form\Type\Video\AddGameToVideoType;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/game/list', name: 'app_game_list')]
    public function index(IgdbBusiness $requestBusiness, Request $request): Response
    {
        $igdbRequest = (new IgdbRequest())
            ->setRouteName('igdb_games')
            ->addField('*')
            ->addField('screenshots.image_id')
            ->addField('platforms.*')
            ->addField('genres.*')
            ->setFieldsNotNullable(['screenshots']);
        $form = $this->createForm(SearchGameType::class, $igdbRequest);
        $form->handleRequest($request);
        if (!empty($platforms = $form->get('platforms')->getData())) {
            $platforms = implode(', ', $platforms);
            $igdbRequest->addCondition("platforms = [$platforms]");
        }

        if (!empty($genres = $form->get('genres')->getData())) {
            $genres = implode(', ', $genres);
            $igdbRequest->addCondition('genres != null');
            $igdbRequest->addCondition("genres = [$genres]");
        }

        $igdbRequest->setOffset(($form->get('page')->getData() - 1) * $igdbRequest->getLimit());
        $results = $requestBusiness->request($igdbRequest);

        return $this->render('Page/Game/list.html.twig', [
            'results' => $results,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/game/{id}', name: 'app_game_details')]
    public function gameDetails(int $id, IgdbBusiness $igdbBusiness, Request $request, VideoRepository $videoRepository): Response
    {
        $addGameToVideoType = new AddGameToVideoModel();
        $videos = $videoRepository->findBy([], ['lastUpdateDateTime' => 'DESC']);
        $form = $this->createForm(AddGameToVideoType::class, $addGameToVideoType, ['videos' => $videos]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $games = $addGameToVideoType->getVideo()->getGames();
            if (!in_array($id, $games)) {
                $games[] = $id;
            }
            $addGameToVideoType->getVideo()->setGames($games);
            $videoRepository->save($addGameToVideoType->getVideo(), true);
        }

        $request = (new IgdbRequest())
            ->setRouteName('igdb_games')
            ->setLimit(1)
            ->addField('*')
            ->addField('screenshots.*')
            ->addField('videos.*')
            ->addField('websites.*')
            ->addField('platforms.*')
            ->addField('genres.*')
            ->addCondition("id = $id");

        $results = $igdbBusiness->request($request);

        return $this->render('Page/Game/details.html.twig', [
            'game' => $results[0],
            'form' => $form?->createView()
        ]);
    }
}
