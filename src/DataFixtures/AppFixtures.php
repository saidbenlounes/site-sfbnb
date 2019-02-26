<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Ad;
use App\Entity\Image;
use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Role;
use App\Entity\Booking;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture {

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager) {


        $faker = Factory::create('fr_FR');
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);
        $adminUser = new User();

        $adminUser->setFirstName('ben')
                ->setLastName('said')
                ->setEmail('ben@gmail.com')
                ->setHash($this->encoder->encodePassword($adminUser, '123'))
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>')
                ->setPicture('http://placehold.it/64x64')
                ->addUserRole($adminRole);
        $manager->persist($adminUser);

        $users = [];
        $genres = ['male', 'female'];

        for ($k = 1; $k <= 10; $k++) {
            $user = new User();
            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';
            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;
            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName)
                    ->setLastName($faker->lastName)
                    ->setEmail($faker->email)
                    ->setIntroduction($faker->sentence())
                    ->setDescription('<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>')
                    ->setHash($hash)
                    ->setPicture($picture);
            $manager->persist($user);
            $users[] = $user;
        }
        for ($i = 1; $i < 30; $i++) {
            $ad = new Ad();
            $title = $faker->sentence();
            $introduction = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';
            $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';

            $user = $users[mt_rand(0, count($users) - 1)];
            $ad->setTitle($title)
                    ->setCoverImage($faker->imageUrl())
                    ->setContent($content)
                    ->setIntroduction($introduction)
                    ->setPrice(mt_rand(40, 200))
                    ->setRooms(mt_rand(1, 5))
                    ->setAuthor($user);
            for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();
                $image->setUrl($faker->imageUrl())
                        ->setCaption($faker->sentence())
                        ->setAd($ad);
                $manager->persist($image);
            }
            for ($j = 1; $j <= mt_rand(0, 10); $j++) {
                $booking = new Booking();
                $createdAt = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->dateTimeBetween('-3 months');
                // Gestion de la date de fin
                $duration = mt_rand(3, 10);

                $endDate = (clone $startDate)->modify("+$duration days");
                $amount = $ad->getPrice() * $duration;
                $booker = $users[mt_rand(0, count($users) - 1)];
                $comment = $faker->paragraph();
                $booking->setBooker($booker)
                        ->setAd($ad)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setCreatedAt($createdAt)
                        ->setComment($comment)
                        ->setAmount($amount);
                $manager->persist($booking);

                if (mt_rand(0, 1)) {
                    $comment = new Comment();
                    $comment->setContent($faker->paragraph())
                            ->setRating(mt_rand(1, 5))
                            ->setAuthor($booker)
                            ->setAd($ad);

                    $manager->persist($comment);
                }
            }

            $manager->persist($ad);
        }

        $manager->flush();
    }

}
