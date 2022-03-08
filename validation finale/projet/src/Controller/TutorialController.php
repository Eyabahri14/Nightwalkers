<?php

namespace App\Controller;

use App\Entity\Tutorial;
use App\Form\TutorialFormType;
use App\Repository\CommandeRepository;
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
 * @package App\Controller
 * @Route("/tutorial")
 */

class TutorialController extends AbstractController
{
    /**
     * @Route("/", name="tutorial_index", methods={"GET"})
     * @param TutorialRepository $tutorialRepository
     * @return Response
     */
    public function index(TutorialRepository $tutorialRepository): Response
    {
        return $this->render('tutorial/index.html.twig', [
            'tutorials' => $tutorialRepository->findAll(),
        ]);
    }


    /**
     * @Route("/{id}", name="tutorial_show", methods={"GET"})
     * @param CommandeRepository $commandeRepository
     * @param Tutorial $tutorial
     * @return Response
     */
    public function show(CommandeRepository $commandeRepository, Tutorial $tutorial): Response
    {
        $commande = $commandeRepository->findOneBy(['user'=>$this->getUser()]);

        return $this->render('tutorial/show.html.twig', [
            'tutorial' => $tutorial,
            'commande'=>$commande
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tutorial_edit", methods={"GET", "POST"})
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
            /** @var UploadedFile $videoFile */
            $videoFile = $form->get('video')->getData();
            if($videoFile)
            {
                $originalFilename = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$videoFile->guessExtension();
                try {
                    $videoFile->move(
                        $this->getParameter('uploads'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $tutorial->setVideo($newFilename);
                $entityManager->flush();
                return $this->redirectToRoute('tutorial_index', [], Response::HTTP_SEE_OTHER);
            }else{
                //$tutorial->setVideo(new File($this->getParameter('uploads').'/'.$tutorial->getVideo()));
                $entityManager->flush();

            }

        }

        return $this->render('tutorial/edit.html.twig', [
            'tutorial' => $tutorial,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tutorial_delete", methods={"POST"})
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

        return $this->redirectToRoute('tutorial_index', [], Response::HTTP_SEE_OTHER);
    }
}
