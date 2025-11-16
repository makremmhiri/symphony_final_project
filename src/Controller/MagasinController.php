<?php

namespace App\Controller;

use App\Entity\Magasin;
use App\Repository\MagasinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MagasinController extends AbstractController
{
    #[Route('/magasin', name: 'app_magasin')]
    public function index(MagasinRepository $magasinRepository): Response
    {
        // Get all magasins from database
        $magasins = $magasinRepository->findAll();

        // Or if you want to order them by name:
        // $magasins = $magasinRepository->findBy([], ['nom' => 'ASC']);

        return $this->render('magasin/index.html.twig', [
            'magasins' => $magasins,
        ]);
    }
}