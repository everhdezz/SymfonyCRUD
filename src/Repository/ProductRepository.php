<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Product $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Product $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function search(string $text = null): QueryBuilder
    {
        $query = $this->createQueryBuilder('p');

        if ($text) {
            $query->andWhere('p.id LIKE :text OR p.name LIKE :text OR p.code LIKE :text')
                ->setParameter('text', '%' . $text . '%')
            ;
        }

        return $query;
    }

    public function getData(): array
    {
        $list = [];
        $products = $this->findAll();

        foreach ($products as $product) {
            $list[] = [
                $product->getId(),
                $product->getCategory(),
                $product->getCode(),
                $product->getName(),
                $product->getDescription(),
                $product->getBrand(),
                $product->getActive() ? 'Si' : 'No',
                $product->getCreatedAt(),
                $product->getUpdatedAt(),
            ];
        }

        return $list;
    }
}
