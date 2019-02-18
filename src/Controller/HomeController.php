<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
    public function home() {
        return $this->render(
                        'home.html.twig', ['title' => 'bonjour a tous twig']
        );
    }

}
