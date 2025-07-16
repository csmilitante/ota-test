<?php

namespace App\Repository;

use App\Entity\JobListing;
use App\Service\NotificationService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobListing>
 */
class JobListingRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ) {
        parent::__construct($registry, JobListing::class);
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function create(array $data): JobListing
    {
        $user = $this->userRepository->find($data['ownerId']);

        $jobListing = new JobListing();
        $jobListing->setTitle($data['title']);
        $jobListing->setDescription($data['description']);
        $jobListing->setStatus($data['status']);
        $jobListing->setOwner($user);

        $this->entityManager->persist($jobListing);
        $this->entityManager->flush();

        return $jobListing;
    }

    public function updateStatus(int $id, $status = 'pending'): array
    {
        $jobListing = $this->find($id);
        $jobListing->setStatus($status);

        $this->entityManager->persist($jobListing);
        $this->entityManager->flush();

        return [
            'id' => $jobListing->getId(),
            'status' => $jobListing->getStatus(),
            'description' => $jobListing->getDescription(),
            'ownerId' => $jobListing->getOwner()->getId(),
            'title' => $jobListing->getTitle(),
        ];
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
