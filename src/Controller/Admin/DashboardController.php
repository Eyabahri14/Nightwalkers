<?php

namespace App\Controller\Admin;

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
    public function index():Response
    {
        return $this->render('admin/home/home.html.twig');
    }
}