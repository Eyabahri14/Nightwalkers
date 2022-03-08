<?php

namespace App\Controller\Admin;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DashboardController
 * @package App\Controller\Admin
 * @Route("/admin/commentaires")
 */
class CommentaireController extends AbstractController
{
    /**
     * @Route("/", name="commentaires_back_index")
     */
    public function index(): Response
    {
        return $this->render('admin/commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }
    /**
     * @Route("/add_Commentaire", name="addCommentaire")
     */
    public function addCommentaire(Request $request) {

        $com = new Commentaire(); // construct vide
        $form = $this->createForm(CommentaireType::class,$com);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($com); // add Product
            $em->flush(); // commit
            // Page ely fiha table ta3 affichage

            return $this->redirectToRoute('affichageCommentaire'); // yhezo lel page ta3 affichage
        }
        return $this->render('admin/commentaire/ajouter_commentaire.html.twig',array('f'=>$form->createView())); // yab9a fi form

    }
    /**
     * @Route("/affichage_commentaire", name="affichageCommentaire")
     */

    public function  affichageCommentaire() {
        $com = $this->getDoctrine()->getManager()->getRepository(Commentaire::class)->findAll(); // select * from product

        return $this->render("admin/commentaire/afficher_commentaire.html.twig",array("commentaire"=>$com));
    }

    /**
     * @Route("/supprimer_commentaire/{id}", name="suppressionCommentaire")
     */
    public function  supprimercommentaire($id) {
        $em= $this->getDoctrine()->getManager();
        $i = $em->getRepository(Commentaire::class)->find($id);

        $em->remove($i);
        $em->flush();

        return $this->redirectToRoute("affichageCommentaire");

    }

    /**
     * @Route("/modifier_commentaire/{id}", name="modificationCommentaire")
     */
    public function modifierCommentaire(Request $req, $id) {
        $em= $this->getDoctrine()->getManager();
        $com = $em->getRepository(Commentaire::class)->find($id);
        $form = $this->createForm(CommentaireType::class,$com);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('affichageCommentaire');

        }
        return $this->render('admin/commentaire/modifier_commentaire.html.twig',array("f"=>$form->createView()));
    }
}
