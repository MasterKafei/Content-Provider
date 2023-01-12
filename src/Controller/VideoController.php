<?php

namespace App\Controller;

use App\Business\IgdbBusiness;
use App\Business\Model\IgdbRequest;
use App\Entity\Video;
use App\Form\Type\Video\CreateVideoType;
use App\Form\Type\Video\UpdateVideoType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/video')]
class VideoController extends AbstractController
{
    #[Route(path: '/create', name: 'app_video_create')]
    public function createVideo(Request $request, VideoRepository $videoRepository): Response
    {
        $video = new Video();
        $form = $this->createForm(CreateVideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $videoRepository->save($video, true);

            return $this->redirectToRoute('app_video_list');
        }

        return $this->render('Page/Video/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/delete/{id}', name: 'app_video_delete')]
    public function deleteVideo(Video $video, VideoRepository $videoRepository): Response
    {
        $videoRepository->remove($video, true);
        return $this->redirectToRoute('app_video_list');
    }

    #[Route(path: '/list', name: 'app_video_list')]
    public function listVideo(VideoRepository $videoRepository): Response
    {
        $videos = $videoRepository->findBy([], ['lastUpdateDateTime' => 'DESC']);
        return $this->render('Page/Video/list.html.twig', [
            'videos' => $videos,
        ]);
    }

    #[Route(path: '/show/{id}', name: 'app_video_show')]
    public function showVideo(Video $video, IgdbBusiness $igdbBusiness): Response
    {
        $conditions = implode(' | ', array_map(function ($gameId) {
            return "id = $gameId";
        }, $video->getGames()));

        $request = new IgdbRequest();
        $request
            ->addField('*')
            ->addField('screenshots.image_id')
            ->addField('platforms.*')
            ->addField('genres.*')
            ->setRouteName('igdb_games')
            ->addCondition($conditions);

        $results = $igdbBusiness->request($request);

        return $this->render('Page/Video/show.html.twig', [
            'video' => $video,
            'results' => $results,
        ]);
    }

    #[Route(path: '/update/{id}', name: 'app_video_update')]
    public function updateVideo(Video $video, Request $request, VideoRepository $videoRepository): Response
    {
        $form = $this->createForm(UpdateVideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $videoRepository->save($video, true);
            return $this->redirectToRoute('app_video_show', [
                'id' => $video->getId(),
            ]);
        }

        return $this->render('Page/Video/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/download/{id}', name: 'app_video_download')]
    public function downloadVideo(Video $video, IgdbBusiness $igdbBusiness): Response
    {
        if (empty($video->getGames())) {
            throw new \RuntimeException('No video games');
        }
        $size = "1080p";

        $igdbRequest = new IgdbRequest();
        $igdbRequest
            ->setRouteName('igdb_games')
            ->addField('name')
            ->addField('screenshots.image_id');
        $condition = implode(' | ', array_map(function (int $game) {
            return "id = $game";
        }, $video->getGames()));
        $igdbRequest->addCondition($condition);
        $response = $igdbBusiness->request($igdbRequest);

        $zip = new \ZipArchive();
        $zipName = "{$video->getName()}.zip";
        $zip->open($zipName, \ZipArchive::CREATE);
        foreach ($response as $game) {
            $game['name'] = str_replace(array('\\', '/', ':', '*', '?', '"', '<', '>', '|'), ' ', $game['name']);
            foreach ($game['screenshots'] as ['image_id' => $screenshot]) {
                $zip->addFromString($game['name'] . "/" . $screenshot . '.png', file_get_contents("https://images.igdb.com/igdb/image/upload/t_$size/$screenshot.jpg"));
            }
        }
        $zip->close();

        $response = new Response(file_get_contents($zipName));
        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $zipName . '"');
        $response->headers->set('Content-length', filesize($zipName));

        @unlink($zipName);

        return $response;
    }

    #[Route(path: '/update/{id}/delete/{game}', name: 'app_video_update_remove_game')]
    public function deleteGame(Video $video, int $game, EntityManagerInterface $entityManager): RedirectResponse
    {
        $games = $video->getGames();
        foreach ($games as $index => $gameId) {
            if ($game == $gameId) {
                unset($games[$index]);
            }
        }

        $video->setGames($games);

        $entityManager->persist($video);
        $entityManager->flush();

        if (empty($video->getGames())) {
            return $this->redirectToRoute('app_video_list');
        }

        return $this->redirectToRoute('app_video_show', ['id' => $video->getId()]);
    }
}