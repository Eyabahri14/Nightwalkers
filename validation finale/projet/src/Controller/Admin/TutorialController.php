<?php

namespace App\Controller\Admin;

use App\Entity\Tutorial;
use App\Form\TutorialFormType;
use App\Repository\TutorialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class TutorialController
 * @package App\Controller\Admin
 * @Route("/admin/tutorial")
 */

class TutorialController extends AbstractController
{
    /**
     * @Route("/", name="tutorial_back_index", methods={"GET"})
     * @param TutorialRepository $tutorialRepository
     * @return Response
     */
    public function index(TutorialRepository $tutorialRepository): Response
    {
        return $this->render('admin/tutorial/index.html.twig', [
            'tutorials' => $tutorialRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tutorial_back_new", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tutorial = new Tutorial();
        $form = $this->createForm(TutorialFormType::class, $tutorial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $videoFile */
            $videoFile = $form->get('video')->getData();

            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            if($videoFile && $imageFile)
            {
                /* upload Video */
                $originalFilename = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$videoFile->guessExtension();

                try {
                    $videoFile->move(
                        $this->getParameter('uploads_tutos'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $tutorial->setVideo($newFilename);


                /* upload Image */
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_tutos'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $tutorial->setImage($newFilename);

                $entityManager->persist($tutorial);
                $entityManager->flush();
                return $this->redirectToRoute('tutorial_back_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('admin/tutorial/add.html.twig', [
            'tutorial' => $tutorial,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tutorial_back_show", methods={"GET"})
     * @param Tutorial $tutorial
     * @return Response
     */
    public function show(Tutorial $tutorial): Response
    {
        return $this->render('admin/tutorial/show.html.twig', [
            'tutorial' => $tutorial,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tutorial_back_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Tutorial $tutorial
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Request $request, Tutorial $tutorial, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TutorialFormType::class, $tutorial);
                $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();
            /** @var UploadedFile $videoFile */
            $videoFile = $form->get('video')->getData();
            if($videoFile && $imageFile )
            {
                $originalFilename = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$videoFile->guessExtension();
                try {
                    $videoFile->move(
                        $this->getParameter('uploads_tutos'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $tutorial->setVideo($newFilename);
                $entityManager->flush();

                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_tutos'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $tutorial->setImage($newFilename);


                $entityManager->flush();




                return $this->redirectToRoute('tutorial_back_index', [], Response::HTTP_SEE_OTHER);
            }else{
                //$tutorial->setVideo(new File($this->getParameter('uploads').'/'.$tutorial->getVideo()));
                $entityManager->flush();

            }


        }

        return $this->render('admin/tutorial/edit.html.twig', [
            'tutorial' => $tutorial,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="tutorial_back_delete", methods={"POST"})
     * @param Request $request
     * @param Tutorial $tutorial
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Request $request, Tutorial $tutorial, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tutorial->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tutorial);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tutorial_back_index', [], Response::HTTP_SEE_OTHER);
    }
}
