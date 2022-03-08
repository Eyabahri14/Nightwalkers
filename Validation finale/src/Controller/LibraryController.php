<?php

namespace App\Controller;

use App\Repository\LibraryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibraryController extends AbstractController
{
    /**
     * @Route("/library", name="library")
     */
    public function index(LibraryRepository $libraryRepository): Response
    {
        $mylib = $libraryRepository->findBy(['userid'=>$this->getUser()]);

        return $this->render('library/index.html.twig', [
            'library' => $mylib,
        ]);
    }
}
