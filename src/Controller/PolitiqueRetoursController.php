<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PolitiqueRetoursController extends AbstractController
{
    #[Route('/politique-de-retours', name: 'politique_retours')]
    public function index(): Response
    {
        return $this->render('politique_retours/index.html.twig', [
            'controller_name' => 'PolitiqueRetoursController',
        ]);
    }
}
