<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Ad;
use App\Entity\Image;
use Faker\Factory;

class AppFixtures extends Fixture {

    public function load(ObjectManager $manager) {


        $faker = Factory::create('fr_FR');
        $title = $faker->sentence();
        for ($i = 1; $i < 30; $i++) {
            $ad = new Ad();

            $introduction = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';
            $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';
            $ad->setTitle($title)
                    ->setCoverImage($faker->imageUrl())
                    ->setContent($content)
                    ->setIntroduction($introduction)
                    ->setPrice(mt_rand(40, 200))
                    ->setRooms(mt_rand(1, 5));
            for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();
                $image->setUrl($faker->imageUrl())
                        ->setCaption($faker->sentence())
                        ->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
        }

        $manager->flush();
    }

}
