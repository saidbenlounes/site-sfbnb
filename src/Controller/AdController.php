<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Ad;
use App\Entity\Image;
use App\Form\AnnonceType;
use App\Repository\AdRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdController extends AbstractController {

    /**
     * @Route("/ads", name="ads_index")
     * @return Response
     */
    public function index(AdRepository $repo) {
        $ads = $repo->findAll();
        return $this->render('ad/index.html.twig', [
                    'ads' => $ads,
        ]);
    }

    /**
     * @Route("/ads/new", name="ads_create")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager) {
        $ad = new Ad();
        $form = $this->createForm(AnnonceType::class, $ad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages()as $image) {
                $image->setAd($ad);

                $manager->persist($image);
            }
            $ad->setAuthor($this->getUser());
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash(
                    'success', "L'annonce <strong>{$ad->getTitle()}</strong> a bien ete enregistrée"
            );
            return $this->redirectToRoute('ads_show', [
                        'slug' => $ad->getSlug()
            ]);
        }


        return $this->render('ad/new.html.twig', [
                    'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()")
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ObjectManager $manager) {

        $form = $this->createForm(AnnonceType::class, $ad);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages()as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();
            $this->addFlash(
                    'success', "les modification de l'annonce <strong>{$ad->getTitle()}</strong> ont bien ete enregistrée"
            );
            return $this->redirectToRoute('ads_show', [
                        'slug' => $ad->getSlug()
            ]);
        }

        $form->handleRequest($request);
        return $this->render('ad/edit.html.twig', [
                    'form' => $form->createView(),
                    'ad' => $ad
        ]);
    }

    /**
     * @Route("/ads/{slug}", name="ads_show")
     * @return Response
     */
    public function show(Ad $ad) {
        return $this->render('ad/show.html.twig', [
                    'ad' => $ad,
        ]);
    }

    /**
     * Permet de supprimer une annonce
     *
     * @Route("/ads/{slug}/delete", name="ads_delete")
     * @Security("is_granted('ROLE_USER') and user == ad.getAuthor()", message="Vous n'avez pas le droit d'accéder à cette ressource")
     *
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Ad $ad, ObjectManager $manager) {
        $manager->remove($ad);
        $manager->flush();
        $this->addFlash(
                'success', "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
        );
        return $this->redirectToRoute("ads_index");
    }

}
