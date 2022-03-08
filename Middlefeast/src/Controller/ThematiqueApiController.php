<?php

namespace App\Controller;

use App\Entity\Thematique;
use App\Entity\Discussion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ThematiqueApiController extends AbstractController
{
    /**
     * @Route("/api/addthem", name="addthem")
     */
    public function addThematique(Request $request) {

        $em = $this->getDoctrine()->getManager();


        // $category_id = $request->get("category_id");
        $nom = $request->get("nom");





        // $category = $em->getRepository(Category::class)->find($category_id);
        $Thematique = new Thematique();
        $Thematique>setNom($nom);

        /*   $Event->setImage($request->get("image"));
           if($request->files->get("image") !=null) {
               $file = $request->files->get("image");
               $fileName = $file->getClientOriginalName();

               $filename = md5(uniqid()) . '.' .$file->guessExtension();//crypté image

               $file->move($this->getParameter('kernel.project_dir').'/public/uploads/Event_image',$filename);


               $Event->setImage($filename);
        }
        */




        $em->persist($Thematique);
        $em->flush();

        //RESPONSE JSON FROM OUR SERVER
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer([$normalizer],[$encoder]);
        $formatted = $serializer->normalize($Thematique);

        return new JsonResponse($formatted);





    }
}
