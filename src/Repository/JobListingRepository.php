<?php

namespace App\Repository;

use App\Entity\JobListing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobListing>
 */
class JobListingRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, JobListing::class);
        $this->entityManager = $entityManager;
    }

    public function create(): JobListing
    {
        $ownerId = rand(1, 10);
        $titleCounter = rand(1, 300);

        $jobListing = new JobListing();
        $jobListing->setTitle('Job Listing '. $titleCounter);
        $jobListing->setDescription('Job Listing ' . $titleCounter . ' Description');
        $jobListing->setStatus('pending');

        $this->entityManager->persist($jobListing);
        $this->entityManager->flush();

        return $jobListing;
    }

    //    /**
    //     * @return JobListing[] Returns an array of JobListing objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('j.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?JobListing
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
