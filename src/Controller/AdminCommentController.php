<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class AdminCommentController extends AbstractController {

    /**
     * @Route("/admin/comments", name="admin_comment_index")
     */
    public function index(CommentRepository $repo) {

        return $this->render('admin/comment/index.html.twig', [
                    'comments' => $repo->findAll()
        ]);
    }

    /**
     * @Route("/admin/comments/{id}/edit", name="admin_comment_edit")
     * @return Response
     */
    public function edit(Comment $comment, Request $request, ObjectManager $manager) {


        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($comment);
            $manager->flush();
            $this->addFlash(
                    'success', "les modification de commentaire N <strong>{$comment->getId()}</strong> ont bien ete enregistrée"
            );

            return $this->redirectToRoute('admin_comment_index');
        }
        return $this->render('admin/comment/edit.html.twig', [
                    'comment' => $comment,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     *
     * @Route("/admin/comments/{id}/delete", name="admin_comment_delete")
     *
     * @param Comment $comment
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Comment $comment, ObjectManager $manager) {
        $manager->remove($comment);
        $manager->flush();
        $this->addFlash(
                'success', "Le commentaire de {$comment->getAuthor()->getFullName()} a bien été supprimé !"
        );
        return $this->redirectToRoute('admin_comment_index');
    }

}
