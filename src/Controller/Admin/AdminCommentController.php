<?php


namespace App\Controller\Admin;


use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
	/**
	 * @Route("/admin/delete/comment/{id}", name="admin.comment.delete")
	 * @param Comment $comment
	 *
	 * @param Request $request
	 *
	 * @return RedirectResponse
	 */
	public function delete(Comment $comment, Request $request, EntityManagerInterface $em)
	{
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->get('_token'))) {
			$em->remove($comment);
			$em->flush();
			$this->addFlash('success', 'Commentaire supprimée avec succès.');
		}
		return $this->redirectToRoute('home');
	}
}