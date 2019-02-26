<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Ad;
use App\Form\AnnonceType;
use App\Entity\Image;

class AdminAccountController extends AbstractController {

    /**
     * @Route("/admin/login", name="admin_account_login")
     */
    public function login(AuthenticationUtils $utils) {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        return $this->render('admin/account/login.html.twig', [
                    'hasError' => $error !== null,
                    'username' => $username
        ]);
    }

    /**
     * @Route("/admin/logout", name="admin_account_logout")
     */
    public function logout() {

    }

    /**
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager) {

        $form = $this->createForm(AnnonceType::class, $ad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($ad);
            $manager->flush();
            $this->addFlash(
                    'success', "les modification de l'annonce <strong>{$ad->getTitle()}</strong> ont bien ete enregistrée"
            );
        }
        return $this->render('admin/ad/edit.html.twig', [
                    'form' => $form->createView(),
                    'ad' => $ad
        ]);
    }

    /**
     * Permet de supprimer une annonce
     *
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     *
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Ad $ad, ObjectManager $manager) {
        if (count($ad->getBookings()) > 0) {
            $this->addFlash(
                    'warning', "L'annonce <strong>{$ad->getTitle()}</strong> ne peut etre supprimer car y a dej a des reservations !"
            );
        } else {
            $manager->remove($ad);
            $manager->flush();
            $this->addFlash(
                    'success', "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
            );
        }
        return $this->redirectToRoute("admin_ads_index");
    }

}
