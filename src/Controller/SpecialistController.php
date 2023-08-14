<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SpecialistRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\CustomerRepository;

class SpecialistController extends AbstractController
{
    #[Route('/specialist/login', name: 'specialist_login')]
    public function Login(Request $request, SpecialistRepository $specialistRepository, SessionInterface $session): Response
    {
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $password = $request->request->get('password');
    
            $specialist = $specialistRepository->findByCredentials($name, $password);
    
            if ($specialist) {
                $pkId = $specialist['pkId'];
                $surname = $specialist['surname'];

                $session->set('pkId', $pkId);

                return $this->redirectToRoute('specialist_main'); 
            } else {
                $this->addFlash('error', 'Invalid credentials!');
            }
        }

        return $this->render('specialist/login.html.twig');
    }

    #[Route('/specialist/main', name: 'specialist_main')]
    public function Main(SessionInterface $session, CustomerRepository $customerRepository)
    {
        $pkId = $session->get('pkId');
        $customers = $customerRepository->findAllBySpecialist($pkId);
        return $this->render('specialist/main.html.twig',[
            'customers' => $customers,
        ]);
    }
}