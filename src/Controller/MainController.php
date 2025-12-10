<?php

namespace App\Controller;
use App\Repository\MagasinRepository;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(
        MagasinRepository $magasinRepository,
        ProduitRepository $produitRepository,
        UserRepository $userRepository
    ): Response
    {
        return $this->render('main/index.html.twig', [
            'magasin_count' => $magasinRepository->count([]),
            'produit_count' => $produitRepository->count([]),
            'user_count' => $userRepository->count([]),
            // Add any other variables you need
        ]);
    }

    #[Route('/search/products', name: 'search_products')]
    public function searchProducts(Request $request): Response
    {
        // Your product search logic here
        $query = $request->query->get('query');
        // Implement product search
        return $this->render('search/products.html.twig', [
            'query' => $query,
            'results' => [] // Add your search results here
        ]);
    }

    #[Route('/search/stores', name: 'search_stores')]
    public function searchStores(Request $request): Response
    {
        // Your store search logic here
        $query = $request->query->get('query');
        // Implement store search
        return $this->render('search/stores.html.twig', [
            'query' => $query,
            'results' => [] // Add your store results here
        ]);
    }
}