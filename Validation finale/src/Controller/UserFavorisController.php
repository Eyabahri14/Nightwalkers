<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Userfavoris;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class UserFavorisController extends AbstractController
{
    private $security;
    public function __construct( Security $security)
    {
        $this->security = $security;
    }
    /**
     * @Route("/user/favoris", name="user_favoris")
     */
    public function index(): Response
    {

        if (!empty($this->security->getUser()) ){
            if ($this->security->getUser()->getUserFav() != null){
                $userfavConnected = $this->security->getUser()->getUserfav()->getId();
                $favoris_article =  $this->getDoctrine()->getManager()->getRepository(Userfavoris::class)->
                GetAllarticle($userfavConnected);

            }
            else
            $favoris_article = [];
        }
        else {
            $favoris_article = [];
        }





        return $this->render('user_favoris/index.html.twig', [
            'favoris_article' => $favoris_article
        ]);
    }
    /**
     * @Route("/remove_from_favoris/{id}/{articleid}", name="remove_from_favoris")
     */
    public function removeFromFavoris($id,$articleid,Request  $request) {
        $userFavoris_id = $this->security->getUser()->getUserfav()->getId();
        $userFavoris=$this->getDoctrine()->getRepository(Userfavoris::class)->find($userFavoris_id);
//        dd($this->getDoctrine()->getRepository(Article::class)->find($articleid));
        $userFavoris->removeArticle($this->getDoctrine()->getRepository(Article::class)->find($articleid));

        $a=$this->getDoctrine()->getManager();
        $a ->remove($userFavoris);
        $a->persist($userFavoris);
        $a->flush();
        return $this->redirectToRoute('home');

    }





}
