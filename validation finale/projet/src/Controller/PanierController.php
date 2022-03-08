<?php

namespace App\Controller;

use App\Entity\Abonnement;
use App\Entity\Commande;
use App\Entity\Formation;
use App\Entity\Library;
use App\Entity\Panier;
use App\Entity\Tutorial;
use App\Entity\User;
use App\Form\PanierType;
use App\Repository\AbonnementRepository;
use App\Repository\CommandeRepository;
use App\Repository\PanierRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

/**
 * @Route("/panier")
 */
class PanierController extends AbstractController
{
    /**
     * @Route("/", name="panier_index", methods={"GET"})
     * @param PanierRepository $panierRepository
     * @return Response
     */
    public function index(PanierRepository $panierRepository): Response
    {
        if($this->getUser()!=null)
        {
            return $this->render('panier/index.html.twig', [
                'paniers' => $panierRepository->findBy(['user'=>$this->getUser()]),
            ]);
        }else
        {
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);

        }

    }


    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @return Response
     * @Route("/addTuto/{id}",name="add_tuto_cart",methods={"GET", "POST"})
     */
    public function createCartItemTutorial(Request $request,EntityManagerInterface $entityManager,$id):Response
    {
        if($this->getUser()!=null)
        {
            $user= $this->getUser();
            $tutorial = $entityManager->getRepository(Tutorial::class)->find($id);

                 $tutorialexist = $entityManager->getRepository('App:Panier')->findOneBy(['tutorial'=>$id]);
                 $formationexist = $entityManager->getRepository('App:Panier')->findOneBy(['formation'=>$id]);

                 if($tutorialexist && !$formationexist)
                 {
                     $tutorialexist->setQte($tutorialexist->getQte()+1);
                     $tutorialexist->setTotal($tutorialexist->getQte()*$tutorial->getPrix());
                     $entityManager->flush();
                 }else{
                    $panier = new Panier();
                    $panier->setTutorial($tutorial);
                    $panier->setFormation(null);
                    $panier->setUser($user);
                    $panier->setQte(1);
                    $panier->setTotal($panier->getQte()*$tutorial->getPrix());
                    $entityManager->persist($panier);
                    $entityManager->flush();
                 }

            return $this->redirectToRoute('tutorial_index', [], Response::HTTP_SEE_OTHER);

        }
        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @return Response
     * @Route("/addFormation/{id}",name="add_formation_cart",methods={"GET", "POST"})
     */
    public function createCartItemFormation(Request $request,EntityManagerInterface $entityManager,$id):Response
    {
        if($this->getUser()!=null)
        {
            $user=$this->getUser();
            $formation = $entityManager->getRepository(Formation::class)->find($id);

            $formationexist = $entityManager->getRepository('App:Panier')->findOneBy(['formation'=>$id]);



            if($formationexist)
            {
                $formationexist->setQte($formationexist->getQte()+1);
                $formationexist->setTotal($formationexist->getQte()*$formation->getPrice());
                $entityManager->flush();
            }else{
                $panier = new Panier();
                $panier->setFormation($formation);
                $panier->setTutorial(null);
                $panier->setUser($user);
                $panier->setQte(1);
                $panier->setTotal($panier->getQte()*$formation->getPrice());
                $entityManager->persist($panier);
                $entityManager->flush();
            }

            return $this->redirectToRoute('formation_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/delete/{id}", name="panier_delete",methods={"GET", "POST"})

     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(EntityManagerInterface $entityManager,$id): Response
    {
            $panier = $entityManager->getRepository(Panier::class)->find($id);
            if($panier)
            {
                $entityManager->remove($panier);
                $entityManager->flush();
            }
        return $this->redirectToRoute('panier_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @param $quantity
     * @param $idp
     * @Route("/updateFormationCart/{id}/{quantity}/{idp}",name="update_formation_cart",methods={"GET", "POST"})
     * @return RedirectResponse
     */
    public function updateQuantityFormation(EntityManagerInterface $entityManager, $id, $quantity, $idp)
    {
        $panier = $entityManager->getRepository(Panier::class)->find($id);

        $formation = $entityManager->getRepository(Formation::class)->find($idp);


        if($panier && $formation)
        {
            $panier->setQte( (int) $quantity);
            $panier->setTotal($formation->getPrice()*(int)$quantity);
            $entityManager->flush();
        }
        return $this->redirectToRoute('panier_index', [], Response::HTTP_SEE_OTHER);

    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @param $quantity
     * @param $idp
     * @Route("/updateTutorialCart/{id}/{quantity}/{idp}",name="update_tutorial_cart",methods={"GET", "POST"})
     * @return RedirectResponse
     */
    public function updateQuantityTutorial(EntityManagerInterface $entityManager, $id, $quantity, $idp)
    {
        $panier = $entityManager->getRepository(Panier::class)->find($id);
        $tutorial = $entityManager->getRepository(Tutorial::class)->find($idp);

        if($panier && $tutorial)
        {
            $panier->setQte((int)$quantity);
            $panier->setTotal($tutorial->getPrix()*(int)$quantity);
            $entityManager->flush();
        }
        return $this->redirectToRoute('panier_index', [], Response::HTTP_SEE_OTHER);

    }

    /**
     * @param CommandeRepository $commandeRepository
     * @param UserRepository $userRepository
     * @param PanierRepository $panierRepository
     * @param EntityManagerInterface $entityManager
     * @param MailerInterface $mailer
     * @return Response
     * @Route("/checkout",name="checkout")
     * @throws TransportExceptionInterface
     */
    public function checkout(CommandeRepository $commandeRepository,UserRepository $userRepository, PanierRepository $panierRepository,EntityManagerInterface $entityManager,MailerInterface $mailer)
    {
        if($this->getUser()!=null)
        {
            $commande = new Commande();


            $commande->setUser($userRepository->find($this->getUser()));
            $commande->setDate(new \DateTime());
            $commande->setEtat("Paid");


            $products = $panierRepository->findBy(['user'=>$this->getUser()]);
            $total = 0;

            $librarytuto = new Library();
            $libraryform = new Library();
            $formationsNames = "";
            foreach ($products as $p)
            {

                if($p->getFormation()->getId()!=null)
                {
                    $formation = $entityManager->getRepository(Formation::class)->find($p->getFormation()->getId());
                    if($formation)
                    {
                         $formationsNames .= $p->getFormation()->getDescription();
                        $libraryform->setUrl("http://127.0.0.1:8000/formation/".$p->getFormation()->getId());
                        $libraryform->setUserid($this->getUser());
                        $entityManager->persist($libraryform);
                        $entityManager->flush();

                        $result = Builder::create()
                            ->writer(new PngWriter())
                            ->labelText("Courses Ticket")
                            ->writerOptions([])
                            ->data("Username : ".$p->getUser()->getUsername()."| Email : ".$p->getUser()->getEmail()."| Course(s) : ".$formationsNames."|")
                            ->encoding(new Encoding('UTF-8'))
                            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                            ->size(300)
                            ->margin(10)
                            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
                            ->labelFont(new NotoSans(20))
                            ->labelAlignment(new LabelAlignmentCenter())
                            ->build();

                        $webPath = $this->getParameter('kernel.project_dir').'/public/';

                        $result->saveToFile($webPath.'/assets/formation.png');
                    }


                }

                if($p->getTutorial()->getId()!=null)
                {
                    $tutorial = $entityManager->getRepository(Tutorial::class)->find($p->getTutorial()->getId());
                    if($tutorial)
                    {
                        $librarytuto->setUrl("http://127.0.0.1:8000/tutorial/".$p->getTutorial()->getId());
                        $librarytuto->setUserid($this->getUser());
                        $entityManager->persist($librarytuto);
                        $entityManager->flush();

                    }


                }




                $total+=$p->getTotal();
            }
            $commande->setTotal($total);

            $entityManager->persist($commande);
            $entityManager->flush();

            $webPath = $this->getParameter('kernel.project_dir').'/public/';

            $email = (new TemplatedEmail())
                ->from(new Address('middlefeastesprit@gmail.com', 'MiddleFeast Mail Bot'))
                ->to($this->getUser()->getEmail())
                ->subject('Order Passed Successfully')
                ->embedFromPath($webPath.'/assets/formation.png')
                ->htmlTemplate('commande/email.html.twig');

            $mailer->send($email);



            foreach ($products as $p)
            {
                $entityManager->remove($p);
                $entityManager->flush();
            }





            return $this->redirectToRoute('showCard',[]);

        }






    }

    /**
     * @Route("/confirmpayment",name="confirmpayment")
     * @param CommandeRepository $commandeRepository
     * @return Response
     * @throws ApiErrorException
     */
    public function confirmPayment(CommandeRepository $commandeRepository) : Response {
        Stripe::setApiKey('sk_test_51KaGREKDbk4B0h2OX7Ee1tFjHoQgjuTBjNLUeWrcJ8rZZEODvzhG1ROtAbCX9UL571HmQL2ccvDkRfXGDIEO5U2Q00eikm6sLI');
        $intent = PaymentIntent::create([
            'amount' => '1000',
            'currency' => 'usd',
            // Verify your integration in this guide by including this parameter
            'metadata' => ['integration_check' => 'accept_a_payment'],
            'receipt_email' => $this->getUser()->getEmail(),
        ]);
        return $this->render('commande/index.html.twig', [
                'commandes' => $commandeRepository->findAll(),
            ]);
    }

    /**
     * @Route("/confirmpayment1",name="confirmpayment1")
     * @param AbonnementRepository $abonnementRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws ApiErrorException
     */
    public function confirmPayment1(AbonnementRepository $abonnementRepository,EntityManagerInterface $entityManager) : Response {
        $abonnement = new Abonnement();
        $abonnement->setPrix(100);
        $abonnement->setUser($this->getUser());
        $abonnement->setDateDebut( new \DateTime());
        $newDate = new \DateTime();
        $newDate->modify('+1 month');
        $abonnement->setDateFin($newDate);

        $entityManager->persist($abonnement);
        $entityManager->flush();


        Stripe::setApiKey('sk_test_51KaGREKDbk4B0h2OX7Ee1tFjHoQgjuTBjNLUeWrcJ8rZZEODvzhG1ROtAbCX9UL571HmQL2ccvDkRfXGDIEO5U2Q00eikm6sLI');
        $intent = PaymentIntent::create([
            'amount' => '100',
            'currency' => 'usd',
            // Verify your integration in this guide by including this parameter
            'metadata' => ['integration_check' => 'accept_a_payment'],
            'receipt_email' => $this->getUser()->getEmail(),
        ]);

        return $this->render('abonnement/mySubscription.html.twig', [
            'sub'=>$abonnementRepository->findOneBy(['user'=>$this->getUser()])
        ]);
    }

    /**
     * @Route("/confirmpayment2",name="confirmpayment2")
     * @param AbonnementRepository $abonnementRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws ApiErrorException
     */
    public function confirmPayment2(AbonnementRepository $abonnementRepository,EntityManagerInterface $entityManager) : Response {
        $abonnement = new Abonnement();
        $abonnement->setPrix(250);
        $abonnement->setUser($this->getUser());
        $abonnement->setDateDebut( new \DateTime());
        $newDate = new \DateTime();
        $newDate->modify('+3 month');
        $abonnement->setDateFin($newDate);

        $entityManager->persist($abonnement);
        $entityManager->flush();

        Stripe::setApiKey('sk_test_51KaGREKDbk4B0h2OX7Ee1tFjHoQgjuTBjNLUeWrcJ8rZZEODvzhG1ROtAbCX9UL571HmQL2ccvDkRfXGDIEO5U2Q00eikm6sLI');
        $intent = PaymentIntent::create([
            'amount' => '250',
            'currency' => 'usd',
            // Verify your integration in this guide by including this parameter
            'metadata' => ['integration_check' => 'accept_a_payment'],
            'receipt_email' => $this->getUser()->getEmail(),
        ]);
        return $this->render('abonnement/mySubscription.html.twig', [
            'sub'=>$abonnementRepository->findOneBy(['user'=>$this->getUser()])
        ]);
    }

    /**
     * @Route("/confirmpayment3",name="confirmpayment3")
     * @param AbonnementRepository $abonnementRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws ApiErrorException
     */
    public function confirmPayment3(AbonnementRepository $abonnementRepository,EntityManagerInterface $entityManager) : Response {
        $abonnement = new Abonnement();
        $abonnement->setPrix(950);
        $abonnement->setUser($this->getUser());
        $abonnement->setDateDebut( new \DateTime());
        $newDate = new \DateTime();
        $newDate->modify('+1 year');
        $abonnement->setDateFin($newDate);

        $entityManager->persist($abonnement);
        $entityManager->flush();
        Stripe::setApiKey('sk_test_51KaGREKDbk4B0h2OX7Ee1tFjHoQgjuTBjNLUeWrcJ8rZZEODvzhG1ROtAbCX9UL571HmQL2ccvDkRfXGDIEO5U2Q00eikm6sLI');
        $intent = PaymentIntent::create([
            'amount' => '950',
            'currency' => 'usd',
            // Verify your integration in this guide by including this parameter
            'metadata' => ['integration_check' => 'accept_a_payment'],
            'receipt_email' => $this->getUser()->getEmail(),
        ]);


        return $this->render('abonnement/mySubscription.html.twig', [
            'sub'=>$abonnementRepository->findOneBy(['user'=>$this->getUser()])
        ]);
    }
}
