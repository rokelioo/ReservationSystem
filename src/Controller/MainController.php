<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController{
    #[Route('/', name: 'start_page')]
    public function index()
    {
        return $this->render('base.html.twig');
    }
}