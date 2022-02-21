<?php

namespace App\Controller;

use App\Entity\Userfavoris;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserFavorisController extends AbstractController
{
    /**
     * @Route("/user/favoris", name="user_favoris")
     */
    public function index(): Response
    {
        $favoris_article =  $this->getDoctrine()->getManager()->getRepository(Userfavoris::class)->GetAllarticle();


//dd($favoris_article);
        return $this->render('user_favoris/index.html.twig', [
            'favoris_article' => $favoris_article
        ]);
    }
    /**
     * @Route("/remove_from_favoris/{id}", name="remove_from_favoris")
     */
    public function removeFromFavoris($id,Request  $request) {
        $userFavoris = $this->getDoctrine()->getRepository(Userfavoris::class)->find($id);

//       $article =new Article();
//      // $fav = new Userfavoris();
//       $fav->removeArticle($userFavoris);
////$article ->removeUserfavori($userFavoris);

        // dd($userFavoris);

        $a=$this->getDoctrine()->getManager();
        $a ->remove($userFavoris);
        $a->persist($userFavoris);

        $a->flush();
        return $this->redirectToRoute('home');

    }





}
