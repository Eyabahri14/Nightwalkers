<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Panier;
use App\Entity\Tutorial;
use App\Entity\User;
use App\Form\PanierType;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/panier")
 */
class PanierController extends AbstractController
{
    /**
     * @Route("/", name="panier_index", methods={"GET"})
     * @param PanierRepository $panierRepository
     * @return Response
     */
    public function index(PanierRepository $panierRepository): Response
    {
        if($this->getUser()!=null)
        {
            return $this->render('panier/index.html.twig', [
                'paniers' => $panierRepository->findBy(['user'=>$this->getUser()]),
            ]);
        }else
        {
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);

        }

    }


    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @return Response
     * @Route("/addTuto/{id}",name="add_tuto_cart")
     */
    public function createCartItemTutorial(Request $request,EntityManagerInterface $entityManager,$id):Response
    {
        if($this->getUser()!=null)
        {
            $user= $this->getUser();
            $tutorial = $entityManager->getRepository(Tutorial::class)->find($id);

                 $tutorialexist = $entityManager->getRepository('App:Panier')->findOneBy(['tutorial'=>$id]);
                 $formationexist = $entityManager->getRepository('App:Panier')->findOneBy(['formation'=>$id]);

                 if($tutorialexist && !$formationexist)
                 {
                     $tutorialexist->setQte($tutorialexist->getQte()+1);
                     $tutorialexist->setTotal($tutorialexist->getQte()*$tutorial->getPrix());
                     $entityManager->flush();
                 }else{
                    $panier = new Panier();
                    $panier->setTutorial($tutorial);
                    $panier->setFormation(null);
                    $panier->setUser($user);
                    $panier->setQte(1);
                    $panier->setTotal($panier->getQte()*$tutorial->getPrix());
                    $entityManager->persist($panier);
                    $entityManager->flush();
                 }

            return $this->redirectToRoute('tutorial_index', [], Response::HTTP_SEE_OTHER);

        }
        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @return Response
     * @Route("/addFormation/{id}",name="add_formation_cart")
     */
    public function createCartItemFormation(Request $request,EntityManagerInterface $entityManager,$id):Response
    {
        if($this->getUser()!=null)
        {
            $user=$this->getUser();
            $formation = $entityManager->getRepository(Formation::class)->find($id);

            $formationexist = $entityManager->getRepository('App:Panier')->findOneBy(['formation'=>$id]);



            if($formationexist)
            {
                $formationexist->setQte($formationexist->getQte()+1);
                $formationexist->setTotal($formationexist->getQte()*$formation->getPrice());
                $entityManager->flush();
            }else{
                $panier = new Panier();
                $panier->setFormation($formation);
                $panier->setTutorial(null);
                $panier->setUser($user);
                $panier->setQte(1);
                $panier->setTotal($panier->getQte()*$formation->getPrice());
                $entityManager->persist($panier);
                $entityManager->flush();
            }

            return $this->redirectToRoute('formation_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/delete/{id}", name="panier_delete")

     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(EntityManagerInterface $entityManager,$id): Response
    {
            $panier = $entityManager->getRepository(Panier::class)->find($id);
            if($panier)
            {
                $entityManager->remove($panier);
                $entityManager->flush();
            }
        return $this->redirectToRoute('panier_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @param $quantity
     * @param $idp
     * @Route("/updateFormationCart/{id}/{quantity}/{idp}",name="update_formation_cart",methods={"GET", "POST"})
     * @return RedirectResponse
     */
    public function updateQuantityFormation(EntityManagerInterface $entityManager, $id, $quantity, $idp)
    {
        $panier = $entityManager->getRepository(Panier::class)->find($id);

        $formation = $entityManager->getRepository(Formation::class)->find($idp);


        if($panier && $formation)
        {
            $panier->setQte( (int) $quantity);
            $panier->setTotal($formation->getPrice()*(int)$quantity);
            $entityManager->flush();
        }
        return $this->redirectToRoute('panier_index', [], Response::HTTP_SEE_OTHER);

    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @param $quantity
     * @param $idp
     * @Route("/updateTutorialCart/{id}/{quantity}/{idp}",name="update_tutorial_cart",methods={"GET", "POST"})
     * @return RedirectResponse
     */
    public function updateQuantityTutorial(EntityManagerInterface $entityManager, $id, $quantity, $idp)
    {
        $panier = $entityManager->getRepository(Panier::class)->find($id);
        $tutorial = $entityManager->getRepository(Tutorial::class)->find($idp);

        if($panier && $tutorial)
        {
            $panier->setQte((int)$quantity);
            $panier->setTotal($tutorial->getPrix()*(int)$quantity);
            $entityManager->flush();
        }
        return $this->redirectToRoute('panier_index', [], Response::HTTP_SEE_OTHER);

    }
}
