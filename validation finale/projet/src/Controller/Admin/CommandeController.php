<?php

namespace App\Controller\Admin;

use App\Form\SearchNameType;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommandeController
 * @package App\Controller\Admin
 * @Route("/admin/commande")
 */

class CommandeController extends AbstractController
{
    /**
     * @Route("/", name="command_back_index")
     * @param CommandeRepository $commandeRepository
     * @return Response
     */
    public function index(CommandeRepository $commandeRepository, Request $request): Response
    {
        return $this->render('admin/commande/index.html.twig',[
            'commandes' => $commandeRepository->findAll(),
        ]);
    }
}