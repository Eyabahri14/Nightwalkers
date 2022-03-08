<?php

namespace App\Controller\Admin;

use App\Repository\CommandeRepository;
use App\Repository\DiscussionRepository;
use App\Repository\FormationRepository;
use App\Repository\ThematiqueRepository;
use App\Repository\TutorialRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class DashboardController
 * @package App\Controller\Admin
 * @Route("/admin")
 */

class DashboardController extends AbstractController{
    /**
     * @Route("/",name="admin")
     */
    public function index(DiscussionRepository $discussionRepository,ThematiqueRepository $thematiqueRepository,CommandeRepository $commandeRepository, FormationRepository $formationRepository, TutorialRepository $tutorialRepository):Response
    {
        $bar = new BarChart();
        $discussions = $discussionRepository->findActiveDiscussion();
        $thematiques = $thematiqueRepository->findActiveThematique();
        $data=[
            ['Thematiques', 'nombre de discussions']
        ];
        foreach ($thematiques as $t){
            $data[]=[$t->getNom(), $t->getNbDiscussions()];
        }
        $bar->getData()->setArrayToDataTable($data);
        $bar->getOptions()->setTitle('Les thematiques les plus actives');
        $bar->getOptions()->getHAxis()->setTitle('Les thematiques les plus actives');
        $bar->getOptions()->getHAxis()->setMinValue(0);
        $bar->getOptions()->getVAxis()->setTitle('Thematique');
        $bar->getOptions()->setWidth(900);
        $bar->getOptions()->setHeight(500);

        $pieChart = new PieChart();
        $data2=[['Discussion', 'Commentaires'],
        ];
        foreach ($discussions as $d){
            $data2[]=[$d->getTitre(), $d->getNbCommentaires()];
        }
        $pieChart->getData()->setArrayToDataTable($data2);

        $pieChart->getOptions()->setTitle('Les disscussions les plus actives');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);


        return $this->render('admin/home/home.html.twig', [
            'bar'=>$bar,
            'pie'=>$pieChart,
            'count_orders' => $commandeRepository->countCommand() > 0 ? $commandeRepository->countCommand() : 0,
            'sum_orders' => $commandeRepository->sumCommand() > 0 ? $commandeRepository->sumCommand() : 0,
            'count_formation' => $formationRepository->countFormation(),
            'count_tutorial' => $tutorialRepository->countTutorial(),
        ]);
    }
}