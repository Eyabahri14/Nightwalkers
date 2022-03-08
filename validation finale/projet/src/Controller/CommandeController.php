<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use App\Repository\PanierRepository;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{

    /**
     * @Route("/commande", name="commande")
     * @param PanierRepository $panierRepository
     * @return Response
     */
    public function index(PanierRepository $panierRepository): Response
    {


    }

    /**
     * @return Response
     * @Route("/showCard",name="showCard")
     */
    public function cardAction(): Response {
        return $this->render('commande/card.html.twig',[]);
    }

    /**
     * @return Response
     * @Route("/showCard1",name="showCard1")
     */
    public function cardAction1(): Response {
        return $this->render('abonnement/ab1.html.twig',[]);
    }

    /**
     * @return Response
     * @Route("/showCard2",name="showCard2")
     */
    public function cardAction2(): Response {
        return $this->render('abonnement/ab2.html.twig',[]);
    }

    /**
     * @return Response
     * @Route("/showCard3",name="showCard3")
     */
    public function cardAction3(): Response {
        return $this->render('abonnement/ab3.html.twig',[]);
    }

    /**
     * @param CommandeRepository $commandeRepository
     * @return Response
     * @Route("/commandes", name="my_orders")
     */
    public function show(CommandeRepository $commandeRepository):Response{
        if($this->getUser()!=null)
        {
            return $this->render('commande/index.html.twig', [
                'commandes' => $commandeRepository->findBy(['user'=>$this->getUser()]),
            ]);


        }else
        {
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);

        }

    }
}
