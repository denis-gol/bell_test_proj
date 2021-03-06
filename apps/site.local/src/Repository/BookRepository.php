<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

     /**
      * @param $value
      * @return Book[] Returns an array of Book objects
      */
    public function findAllBooksByNameWithItsAuthors($value): array
    {
        return $this->createQueryBuilder('book')
            ->leftJoin('book.author', 'authors')
            ->addSelect('authors')

            ->leftJoin('App\Entity\BookTranslation', 'book_tr', Join::WITH, 'book.id=book_tr.translatable_id')
            ->addSelect('book_tr')

            ->andWhere('book_tr.name = :val')
            ->setParameter('val', $value)

            ->orderBy('book.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
