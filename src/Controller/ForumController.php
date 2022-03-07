<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Entity\Thematique;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    /**
     * @Route("/forum", name="forum")
     */
    public function index(): Response
    {
        $thematiques = $this->getDoctrine()->getManager()->getRepository(Thematique::class)->findAll(); // select * from product

        return $this->render('forum/index.html.twig', array("thematiques"=>$thematiques));
    }
    /**
     * @Route("/discussions/{id}", name="discussions")
     */
    public function listDiscussions($id){
        $discussions = $this->getDoctrine()->getManager()->getRepository(Discussion::class)->findBy(['thematique'=>$id]);

        return $this->render('forum/discussions.html.twig', array("discussions"=>$discussions));
    }

    /**
     * @Route("/commentaires/{id}", name="commentaires")
     */
    public function listCommentaires($id,Request $request){
        $commentaires = $this->getDoctrine()->getManager()->getRepository(Commentaire::class)->findBy(['discussion'=>$id]);
        $discussion = $this->getDoctrine()->getManager()->getRepository(Discussion::class)->find($id);
        $com = new Commentaire(); // construct vide
        $com->setDiscussion($discussion);
        $com->setParent(0);
        $com->setNblike(0);
        $form = $this->createForm(CommentaireType::class,$com);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($com); // add Product
            $em->flush(); // commit
            // Page ely fiha table ta3 affichage

            return $this->redirect($request->getUri());
        }
        return $this->render('forum/commentaires.html.twig', array("commentaires"=>$commentaires,'form'=>$form->createView()));
    }

    /**
     * @Route("/likecommentaires/{id}", name="likecommentaires")
     */
    public function likeCommentaires($id){
        //recupération du commentaire avec id=$id
        $commentaire= $this->getDoctrine()->getManager()->getRepository(Commentaire::class)->find($id);
        //recupere l'id discussion
        $idDiscussion=$commentaire->getDiscussion()->getId();
        //dump($idDiscussion);
        //recuperer le nombre de like
        $nblike=$commentaire->getNblike();//je récupère le nbre de jaime de ce commentaire
        $nblike++;//j'incremente le nombre de jaime
        //on affecte le nouveau nblike au commentaire
        $commentaire->setNblike($nblike);
        //on met à jour la base de données
        $em = $this->getDoctrine()->getManager();
        $em->persist($commentaire); // add Product
        $em->flush();
        // redirection vers page précedente
        return $this->redirectToRoute('commentaires',array('id'=>$idDiscussion));

    }

}
