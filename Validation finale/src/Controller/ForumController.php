<?php

namespace App\Controller;

use App\Entity\Discussion;
use App\Entity\Thematique;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Knp\Snappy\Pdf;
//use MercurySeries\FlashyBundle\FlashyNotifier;
use App\Repository\CommentaireRepository;
use App\Repository\DiscussionRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    private $repository;
    private $discussionRepository;
    private $knpsnappypdf;
   // private $flashy;

    public function __construct(CommentaireRepository $repository,DiscussionRepository $Discussionrepository,Pdf $KnpSnappyPdf){
        $this->repository = $repository;
        $this->discussionRepository=$Discussionrepository;
        $this->knpsnappypdf=$KnpSnappyPdf;
        //$this->flashy = $flashy;
    }
    /**
     * @Route("/forum", name="forum")
     */
    public function index(): Response
    {
        $thematiques = $this->getDoctrine()->getManager()->getRepository(Thematique::class)->findAll(); // select * from product
        $discussions = $this->discussionRepository->findActiveDiscussion();
        return $this->render('forum/index.html.twig', array("thematiques"=>$thematiques,'discussions'=>$discussions));
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
    public function listCommentaires($id, PaginatorInterface $paginator,Request $request){
        $commentaires = $this->getDoctrine()->getManager()->getRepository(Commentaire::class)->findBy(['discussion'=>$id]);

        $pagination = $paginator->paginate(
            $this->repository->findByDiscussion($id), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );

        $discussion = $this->getDoctrine()->getManager()->getRepository(Discussion::class)->find($id);
        $com = new Commentaire(); // construct vide
        $com->setDiscussion($discussion);
        $com->setParent(0);
        $com->setNblike(0);
        $form = $this->createForm(CommentaireType::class,$com);
        $form->handleRequest($request);
        $myDictionary = array(
            array("tue","con"),
            "gueule",
            "débile",
            "clochard",
            "merde",
            "dégeulasse",
            "térroriste",
            "sang"
        );
        if($form->isSubmitted() && $form->isValid()) {
            $myText = $request->get("commentaire")['description'];
            $badwords = new PhpBadWordsController();
            $badwords->setDictionaryFromArray( $myDictionary )
                ->setText( $myText );
            $check =$badwords->check();
            dump($check);

            if( $check ){
                //$this->flashy->error('Mot inapproprié!', '');
                $this->addFlash('Erreur','Mot inapproprié!');
                //$this->redirect($request->getUri());
            }else{
                $em = $this->getDoctrine()->getManager();
                $em->persist($com); // add Product
                $em->flush(); // commit
                // Page ely fiha table ta3 affichage
                $this->addFlash('Success','Commentaire ajouté');

            }
            return $this->redirect($request->getUri());
        }
        return $this->render('forum/commentaires.html.twig', array('commentaires'=>$commentaires,'pagination' => $pagination,'form'=>$form->createView(),'discussion'=>$discussion));
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



    /**
     * @Route("/commentaire/{id}", name="commentaire")
     */
    public function detailCommentaire($id)
    {
        //recupération du commentaire avec id=$id
        $commentaire = $this->getDoctrine()->getManager()->getRepository(Commentaire::class)->find($id);
        //recupere l'id discussion
        $discussion = $commentaire->getDiscussion()->getTitre();

        $html= $this->render('forum/commentaire_detail.html.twig', array("discussion"=>$discussion,'commentaire'=>$commentaire));
        return new PdfResponse(

            $this->knpsnappypdf->getOutputFromHtml(utf8_decode($html)),
            'File'.$id.'.pdf'
        );
        /*

        */
    }







    /**
     * @Route("/detail_produit/{id}", name="detail")
     */
    public function detailThematique(Request $req, $id) {
        $em= $this->getDoctrine()->getManager();
        $prod = $em->getRepository(Thematique::class)->find($id);


        return $this->render('forum/detail_thematique.html.twig',array(
            'id'=>$prod->getId(),
            'nom'=>$prod->getNom(),
            'image'=>$prod->getimage(),

        ));


    }






}
