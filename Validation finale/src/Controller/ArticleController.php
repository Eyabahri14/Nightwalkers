<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Userfavoris;
use App\Entity\Vote;
use App\Form\ArticleType;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ArticleRepository;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
class ArticleController extends AbstractController
{

    private $security;
    public function __construct( Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/article", name="article")
     */
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'products' =>  $this->getDoctrine()->getManager()->getRepository(Article::class)->findAll()
        ,
        ]);
    }

    /**
     * @Route("/add_article", name="add_article")
     */
    public function add_article(Request  $request) {

        $prod = new Article(); // construct vide
        $form = $this->createForm(ArticleType::class,$prod);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

//            picture uploaded filed

            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('picture')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $newFilename = $originalFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $prod->setPicture($newFilename);

                //set date now for post
                $prod->setDate(new \DateTime());
            }



            $em = $this->getDoctrine()->getManager();
            $em->persist($prod); // commit produit
            $em->flush(); //push table
            // Page ely fiha table ta3 affichage

            return $this->redirectToRoute('article'); // yhezo lel page ta3 affichage
        }

        return $this->render('article/ajouter_article_by_user.html.twig',array('f'=>$form->createView())); // yab9a fi form
        return $this->render('article/ajouter_article.html.twig',array('f'=>$form->createView())); // yab9a fi form

    }

    /**
     * @Route("/publier_article", name="publier_artcile")
     */
    public function publier_artcile(Request  $request) {

        $prod = new Article(); // construct vide
        $form = $this->createForm(ArticleType::class,$prod);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

//            picture uploaded filed

            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('picture')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $newFilename = $originalFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $prod->setPicture($newFilename);

                //set date now for post
                $prod->setDate(new \DateTime());
            }



            $em = $this->getDoctrine()->getManager();
            $em->persist($prod); // commit produit
            $em->flush(); //push table
            // Page ely fiha table ta3 affichage

            return $this->redirectToRoute('article'); // yhezo lel page ta3 affichage
        }

        return $this->render('article/ajouter_article_by_user.html.twig',array('f'=>$form->createView())); // yab9a fi form

    }


    /**
     * @Route("/affichage_article/{id}", name="affichage_article")
     */

    public function  affichage_article($id) {
        $product = $this->getDoctrine()->getManager()->getRepository(Article::class)->find($id); // select * from product

        $entityManager =  $this->getDoctrine()->getManager();
        $product->setvues($product->getVues()+1);
        $entityManager->flush();
        return $this->render("article/affichage_article.html.twig",array("product"=>$product));
    }

    /**
     * @Route("/supprimer_article/{id}", name="supprimer_article")
     */
    public function  supprimer_article($id) {
        $em= $this->getDoctrine()->getManager();
        $i = $em->getRepository(Article::class)->find($id);

        $em->remove($i);
        $em->flush();

        return $this->redirectToRoute("article");

    }
    /**
     * @Route ("/modifier/{id}", name="modifier_article")
     */
    public function modifier_article($id,Request $req)
    {
        $products=$this->getDoctrine()->getRepository(Article::class)->find($id);
        $form=$this->createForm(ArticleType::class,$products);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();

            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('picture')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $newFilename = $originalFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $products->setPicture($newFilename);
            }



            $a=$this->getDoctrine()->getManager();
            $a->persist($data);

            $a->flush();
            return $this->redirectToRoute('article');
        }
        return $this->render('article/modifier_article.html.twig',array(
            'f'=>$form->createView(),
            'product'=>$products
        ));
    }


    /**
     * @Route("/add_to_favoris/{id}/", name="add_to_favoris")
     */
    public function addToFavoris($id,Request  $request){

        $userFavoris_id = $this->security->getUser()->getUserfav()->getId();
        $userFavoris=$this->getDoctrine()->getRepository(Userfavoris::class)->find($userFavoris_id);

        $userFavoris->addArticle($this->getDoctrine()->getRepository(Article::class)->find($id));
        $a=$this->getDoctrine()->getManager();
        $a->persist($userFavoris);

        $a->flush();
        $this->addFlash('success',"l'article a été ajouté avec succée");
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/article/like/{id}", name="like")
     */


    public function like ($id,Request  $request){

//        get article by id start
        $article =$this->getDoctrine()->getRepository(Article::class)->find($id);
//        get article by id end


//        count like start

            $array = $article->getVoteUserId();


        array_push($array,$this->security->getUser()->getId());

        $article->setVoteUserId($array);
        $a=$this->getDoctrine()->getManager();
        $a->persist($article);
        $a->flush();
        $this->addFlash('success',"like");
        return $this->redirectToRoute('home');
    }
    /**
     * @Route("api/article", name="findAllByApi")
     */
    public function getAllArticleforPiproject(ArticleRepository $articlesRepo){
        // On récupère la liste des articles
        $articles = $articlesRepo->findAll();

        // On spécifie qu'on utilise l'encodeur JSON
        $encoders = [new JsonEncoder()];

        // On instancie le "normaliseur" pour convertir la collection en tableau
        $normalizers = [new ObjectNormalizer()];

        // On instancie le convertisseur
        $serializer = new Serializer($normalizers, $encoders);

        // On convertit en json
        $jsonContent = $serializer->serialize($articles, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        // On instancie la réponse
        $response = new Response($jsonContent);

        // On ajoute l'entête HTTP
        $response->headers->set('Content-Type', 'application/json');

        // On envoie la réponse
        return $response;

    }
    /**
     * @Route("api/article/supprimer/{id}", name="supprime")
     */
    public function removeArticle(Article $article)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();
        return new Response('ok');
    }
    /**
     * @Route("api/article/lire/{id}", name="getonearticle")
     */
    public function getArticle(Article $article)
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($article, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    /**
     * @Route("api/article/ajout", name="ajout")
     */
    public function addArticle(Request $request)
    {
        // On vérifie si la requête est une requête Ajax
        if($request->isXmlHttpRequest()) {
            // On instancie un nouvel article
            $article = new Article();

            // On décode les données envoyées
            $donnees = json_decode($request->getContent());

            // On hydrate l'objet
            $article->setTitre($donnees->titre);
            $article->setContenu($donnees->contenu);
            $article->setFeaturedImage($donnees->image);
            $user = $this->getDoctrine()->getRepository(Users::class)->findOneBy(["id" => 1]);
            $article->setUsers($user);

            // On sauvegarde en base
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            // On retourne la confirmation
            return new Response('ok', 201);
        }
        return new Response('Failed', 404);
    }

    /**
     * @Route("chart", name="chart")
     */

    public function articlesStatistic(Request $request)
    {


        $articles = $this->getDoctrine()->getManager()->getRepository(Article::class)->findAll();
        //        vues per article

        $vuesperarticle=[];
        $vuesperarticle['name']='number';
        foreach ($articles as $vues){
            $b= $vues->getName();
            $vuesperarticle[$b]=$vues->getVues();
        }

//        likes per article

        $likesperarticle=[];
        $likesperarticle['name']='number';
        foreach ($articles as $likes){
            $b= $likes->getName();
            $likesperarticle[$b]=sizeof($likes->getVoteUserId());
        }

        return $this->render('article/chart_article.html.twig',array(
            'vuesperarticle' =>  $vuesperarticle,
            'likesperarticle' =>$likesperarticle
        ));
    }

}

