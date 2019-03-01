<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Repository\AdRepository;
use App\Repository\UserRepository;

class HomeController extends Controller {

    /**
     * @Route("/hello/{prenom}/{age}", name="test")
     */
    public function hello($prenom = "test", $age = 30) {
        return $this->render(
                        'hello.html.twig', ['prenom' => $prenom, 'age' => $age]
        );
    }

    /**
     * @Route("/", name="homepage")
     */
    public function home(AdRepository $adRepo, UserRepository $userRepo) {
        return $this->render(
                        'home.html.twig', [
                    'ads' => $adRepo->findBestAds(3),
                    'users' => $userRepo->findBestUsers(2)
                        ]
        );
    }

}
