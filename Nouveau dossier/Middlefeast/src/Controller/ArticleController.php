<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Userfavoris;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{
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

//dd($prod);

            $em = $this->getDoctrine()->getManager();
            $em->persist($prod); // commit produit
            $em->flush(); //push table
            // Page ely fiha table ta3 affichage

            return $this->redirectToRoute('article'); // yhezo lel page ta3 affichage
        }
        return $this->render('article/ajouter_article.html.twig',array('f'=>$form->createView())); // yab9a fi form

    }
    /**
     * @Route("/affichage_article", name="affichage_article")
     */

    public function  affichage_article() {
        $products = $this->getDoctrine()->getManager()->getRepository(Article::class)->findAll(); // select * from product


        return $this->render("article/affichage_article.html.twig",array("products"=>$products));
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
     * @Route("/add_to_favoris/{id}", name="add_to_favoris")
     */
    public function addToFavoris($id,Request  $request){

        $userFavoris = new Userfavoris();
        $userFavoris->addArticle($this->getDoctrine()->getRepository(Article::class)->find($id));

        $a=$this->getDoctrine()->getManager();
        $a->persist($userFavoris);

        $a->flush();
        $this->addFlash('success',"l'article a été ajouté avec succée");
//        dd($userFavoris);
        return $this->redirectToRoute('home');


    }





}

