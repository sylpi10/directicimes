<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhotosController extends AbstractController
{
    /**
     * @Route("/photos", name="photos")
     */
    public function pictures(AlbumRepository $albumRepository)
    {
        $albums = $albumRepository->findAll();
        return $this->render('photos/photos.html.twig', [
            'albums' => $albums
        ]);
    }
}
