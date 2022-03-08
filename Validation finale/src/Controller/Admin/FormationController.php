<?php

namespace App\Controller\Admin;

use App\Entity\Formation;
use App\Form\FormationFormType;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FormationController
 * @package App\Controller\Admin
 * @Route("/admin/formation")
 */

class FormationController extends AbstractController
{
    /**
     * @Route("/", name="formation_back_index")
     * @param FormationRepository $formationRepository
     * @return Response
     */
    public function index(FormationRepository $formationRepository): Response
    {
        return $this->render('admin/formation/index.html.twig', [
            'formations' => $formationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="formation_back_new")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationFormType::class, $formation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('formation_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/formation/add.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="formation_back_show")
     * @param Formation $formation
     * @return Response
     */
    public function show(Formation $formation): Response
    {
        return $this->render('admin/formation/show.html.twig',[
            'formation' => $formation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="formation_back_edit")
     * @param Request $request
     * @param Formation $formation
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Request $request, Formation $formation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormationFormType::class, $formation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('formation_back_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
        ]);
    }
  
    /**
     * @Route("/delete/{id}", name="formation_back_delete")
     * @param Request $request
     * @param Formation $formation
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Request $request, Formation $formation, EntityManagerInterface $entityManager): Response
    {
        $formation = $entityManager->getRepository(Formation::class)->find($formation->getId());
        $entityManager->remove($formation);
        $entityManager->flush();


        return $this->redirectToRoute('formation_back_index', [], Response::HTTP_SEE_OTHER);
    }
}
