<?php

namespace App\Controller;

use App\Entity\Thematique;
use App\Form\ThematiqueFormType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThematiqueController extends AbstractController
{
    /**
     * @Route("/thematique", name="thematique")
     */
    public function index(): Response
    {
        return $this->render('thematique/index.html.twig', [
            'controller_name' => 'ThematiqueController',
        ]);
    }
    /**
     * @Route("/add_thematique", name="addThematique")
     */
    public function addThematique(Request  $request) {

        $them = new Thematique(); // construct vide
        $form = $this->createForm(ThematiqueFormType::class,$them);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $uploadFile = $form['image']->getData();
            $filename = md5(uniqid()) . '.' .$uploadFile->guessExtension();

            $uploadFile->move($this->getParameter('kernel.project_dir').'/public/uploads/thematique_image',$filename);
            $them->setImage($filename);
            $em = $this->getDoctrine()->getManager();
            $em->persist($them); // add Product
            $em->flush(); // commit

            // Page ely fiha table ta3 affichage
            return $this->redirectToRoute('affichageThematique'); // yhezo lel page ta3 affichage
        }
        return $this->render('thematique/ajouter_thematique.html.twig',array('f'=>$form->createView())); // yab9a fi form

    }
    /**
     * @Route("/affichage_thematique", name="affichageThematique")
     */

    public function  affichageThematique() {
        $them = $this->getDoctrine()->getManager()->getRepository(Thematique::class)->findAll(); // select * from product

        return $this->render("thematique/afficher_thematique.html.twig",array("thematique"=>$them));
    }

    /**
     * @Route("/supprimer_thematique/{id}", name="suppressionThematique")
     */
    public function  supprimerThematique($id) {
        $em= $this->getDoctrine()->getManager();
        $i = $em->getRepository(Thematique::class)->find($id);

        $em->remove($i);
        $em->flush();

        return $this->redirectToRoute("affichageThematique");

    }

    /**
     * @Route("/modifier_thematique/{id}", name="modificationThematique")
     */
    public function modifierThematique(Request $req, $id) {
        $em= $this->getDoctrine()->getManager();
        $them = $em->getRepository(Thematique::class)->find($id);
        $form = $this->createForm(ThematiqueFormType::class,$them);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()) {
            $uploadFile = $form['image']->getData();
            $filename = md5(uniqid()) . '.' .$uploadFile->guessExtension();

            $uploadFile->move($this->getParameter('kernel.project_dir').'/public/uploads/thematique_image',$filename);
            $them->setImage($filename);
            $em->flush();

            return $this->redirectToRoute('affichageThematique');

        }

        return $this->render('thematique/modifier_thematique.html.twig',array("f"=>$form->createView()));


    }

    //SEARCH

    /**
     * @Route("/ajax_search/", name="ajax_search")
     */
    public function chercherThematique(\Symfony\Component\HttpFoundation\Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');// ooofkdokfdfdf
        $thematique=  $em->getRepository(Thematique::class)->rechercheAvance($requestString);
        if(!$thematique) {
            $result['thematique']['error'] = "Thematique non trouvé :( ";
        } else {
            $result['thematique'] = $this->getRealEntities($thematique);
        }
        return new Response(json_encode($result));
    }





    // LES  attributs
    public function getRealEntities($thematique){
        foreach ($thematique as $thematique){
            $realEntities[$thematique->getId()] = [$thematique->getImage(),$thematique->getNom()];

        }
        return $realEntities;
    }


}
