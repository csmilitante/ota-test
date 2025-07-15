<?php

namespace App\DataFixtures;

use App\Entity\JobListing;
use App\Entity\User;
use App\Factory\JobListingFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::new()->createMany(10);

        UserFactory::new()
            ->isModerator()
            ->create();

        JobListingFactory::createMany(20, function () {
            return [
                'owner' => UserFactory::random([
                    'isModerator' => false,
                ]),
            ];
        });

        UserFactory::new()->createMany(5);
    }
}
