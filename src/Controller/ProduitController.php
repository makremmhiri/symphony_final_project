<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
public function index(Request $request, ProduitRepository $produitRepository): Response
{
    // Récupérer les paramètres GET avec valeurs par défaut
    $searchTerm = $request->query->get('search') ?? '';
    $selectedMarque = $request->query->get('marque') ?? '';
    $selectedStock = $request->query->get('stock') ?? '';

    $produits = $produitRepository->findByFilters($searchTerm, $selectedMarque, $selectedStock);
    $marques = $produitRepository->findUniqueMarques();

    return $this->render('produit/index.html.twig', [
        'produits' => $produits,
        'marques' => $marques,
        'searchTerm' => $searchTerm,
        'selectedMarque' => $selectedMarque,
        'selectedStock' => $selectedStock
    ]);
}
}