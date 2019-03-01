<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;

class AdminUserController extends AbstractController {

    /**
     * @Route("/admin/users", name="admin_users_index")
     */
    public function index(UserRepository $users) {
        return $this->render('admin/user/index.html.twig', [
                    'users' => $users->findAll()
        ]);
    }

}
