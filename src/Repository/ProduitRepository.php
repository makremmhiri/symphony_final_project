<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }
    // Dans ProduitRepository.php
// Dans ProduitRepository.php
public function findByFilters(?string $search, ?string $marque, ?string $stock): array
{
    $qb = $this->createQueryBuilder('p')
        ->leftJoin('p.id_mag', 'm')  // Jointure avec Magasin
        ->addSelect('m');           // Sélectionne aussi les données du magasin

    if ($search && trim($search) !== '') {
        $qb->andWhere('p.nom_prod LIKE :search OR p.description LIKE :search')
           ->setParameter('search', '%' . trim($search) . '%');
    }

    if ($marque && trim($marque) !== '') {
        $qb->andWhere('p.marque = :marque')
           ->setParameter('marque', trim($marque));
    }

    if ($stock && trim($stock) !== '') {
        switch (trim($stock)) {
            case 'inStock':
                $qb->andWhere('p.quatite_disp > 10');
                break;
            case 'lowStock':
                $qb->andWhere('p.quatite_disp > 0 AND p.quatite_disp <= 10');
                break;
            case 'outOfStock':
                $qb->andWhere('p.quatite_disp = 0');
                break;
        }
    }

    return $qb->getQuery()->getResult();
}

    public function findUniqueMarques(): array{
        $query = $this->createQueryBuilder('p')
            ->select('DISTINCT p.marque')
            ->where('p.marque IS NOT NULL')
            ->orderBy('p.marque', 'ASC')
            ->getQuery();

        return array_column($query->getScalarResult(), 'marque');
    }
//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
