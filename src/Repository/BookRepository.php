<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
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

    public function findByid($id)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.id=:id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
    public function orderByAuthorName(){
        return $this->createQueryBuilder('b')
        ->leftJoin('b.Author', 'a') 
        ->orderBy('a.username', 'ASC') 
        ->getQuery()
        ->getResult();
    }
    public function findBooksBeforeYearWithnbbooks()
    {
        
        $entityManager = $this->getEntityManager();

        return $entityManager->createQueryBuilder()
        
        ->select('b')
        ->from(Book::class, 'b')
        ->join('b.Author', 'a')
        ->where('b.publicationDate < :year')
        ->andWhere('a.nb_books > :bookCount')
        ->setParameter('year', 2023)
        ->setParameter('bookCount', 35)
        ->getQuery()
        ->getResult();
    }
    public function updateCategoryForShakespeareBooks()
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.Author', 'a')
            ->where('a.username = :authorName')
            ->setParameter('authorName', 'williamSahkespeare')
            ->getQuery()
            ->getResult();
    }
    public function sumScienceFictionBooks()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery('
            SELECT SUM(b.published) 
            FROM App\Entity\Book b 
            WHERE b.category = :category
        ');

        $query->setParameter('category', 'Science Fiction');

        return $query->getSingleScalarResult();
    }
    public function findBooksBetweenDates($startDate,$endDate)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery('
            SELECT b
            FROM App\Entity\Book b
            WHERE b.publicationDate BETWEEN :startDate AND :endDate
        ');

        $query->setParameter('startDate', $startDate);
        $query->setParameter('endDate', $endDate);

        return $query->getResult();
    }


}
//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

