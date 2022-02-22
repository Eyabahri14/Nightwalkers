<?php

namespace App\Controller;
use App\Entity\Discussion;
use App\Form\DiscussionType;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiscussionController extends AbstractController
{
    /**
     * @Route("/back", name="discussion")
     */
    public function index(): Response
    {
        return $this->render('discussion/index.html.twig', [
            'controller_name' => 'DiscussionController',
        ]);
    }

    /**
     * @Route("/add_discussion", name="add")
     */
    public function addDiscussion(Request $request) {

        $disc = new Discussion(); // construct vide
        $form = $this->createForm(DiscussionType::class,$disc);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($disc); // add Product
            $em->flush(); // commit
            // Page ely fiha table ta3 affichage

            return $this->redirectToRoute('affichage'); // yhezo lel page ta3 affichage
        }
        return $this->render('discussion/ajouter_discussion.html.twig',array('f'=>$form->createView())); // yab9a fi form

    }
    /**
     * @Route("/affichage_discussion", name="affichage")
     */

    public function  affichagediscussion() {
        $disc = $this->getDoctrine()->getManager()->getRepository(Discussion::class)->findAll(); // select * from product

        return $this->render("discussion/afficher_discussion.html.twig",array("discussion"=>$disc));
    }

    /**
     * @Route("/supprimer_discussion/{id}", name="suppression")
     */
    public function  supprimerdiscussion($id) {
        $em= $this->getDoctrine()->getManager();
        $i = $em->getRepository(Discussion::class)->find($id);

        $em->remove($i);
        $em->flush();

        return $this->redirectToRoute("affichage");

    }

    /**
     * @Route("/modifier_discussion/{id}", name="modification")
     */
    public function modifierDiscussion(Request $req, $id) {
        $em= $this->getDoctrine()->getManager();
        $disc = $em->getRepository(Discussion::class)->find($id);
        $form = $this->createForm(DiscussionType::class,$disc);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('affichage');

        }

        return $this->render('discussion/modifier_discussion.html.twig',array("f"=>$form->createView()));


    }


}


