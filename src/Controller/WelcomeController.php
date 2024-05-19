<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class WelcomeController extends AbstractController
{
    #[Route('/', name: 'welcome')]
    public function index(): Response
    {
        return $this->render('welcome/index.html.twig');
    }

    #[Route('/connected/welcome', name: 'welcomeConnected')]
    public function welcomeConnected(): Response
    {
        $user = $this->getUser();
        $user = $user->getUsername();
        return $this->render('welcome/connected.html.twig', ['username' => $user]);
    }



}
