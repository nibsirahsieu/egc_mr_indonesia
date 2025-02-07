<?php

namespace App\DataFixtures;

use App\Entity\PostType;
use App\Entity\Sector;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // user admin
        $admin = new User();
        $admin->setEmail('admin@egcmea.com');
        $admin->setPassword('$2y$13$kHWVQtNmF/KfSYOFZ8UnPeMkuBjGN.C3/CPN/.ckUkEXIDJBTsSnm'); // 1
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        // post type
        $articleType = new PostType();
        $articleType->setName('Article');
        $manager->persist($articleType);

        $whitepaperType = new PostType();
        $whitepaperType->setName('Whitepaper');
        $manager->persist($whitepaperType);

        // sector
        $construction = new Sector();
        $construction->setName('Construction');
        $manager->persist($construction);

        $healthcare = new Sector();
        $healthcare->setName('Healthcare');
        $manager->persist($healthcare);

        $logistic = new Sector();
        $logistic->setName('Supply Chain & Logistic');
        $manager->persist($logistic);

        $transport = new Sector();
        $transport->setName('Transport & Mobility');
        $manager->persist($transport);

        $consumerGoods = new Sector();
        $consumerGoods->setName('Consumer Goods');
        $manager->persist($consumerGoods);

        $manager->flush();
    }
}
