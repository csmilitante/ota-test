<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $entityManager;
    }

    public function create()
    {
        // create moderator
        $moderator = new User();
        $moderator->setEmail('csd.militante@gmail.com');
        $moderator->setFirstName('Stacy');
        $moderator->setLastName('Militante');
        $moderator->setIsModerator(true);

        $this->entityManager->persist($moderator);
        $this->entityManager->flush();

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail('csd.militante+user' . $i . '@gmail.com');
            $user->setFirstName('Stacy - User - ' . $i);
            $user->setLastName('Militante');

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
