<?php


namespace App\Controller\Admin;


use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCommentController extends AbstractController
{
	/**
	 * @Route("/admin/delete/comment/{id}", name="admin.comment.delete")
	 * @param Comment                $comment
	 *
	 * @param Request                $request
	 * @param EntityManagerInterface $em
	 *
	 * @return JsonResponse
	 */
	public function delete(Comment $comment, Request $request, EntityManagerInterface $em)
	{
		$data = json_decode($request->getContent(), true);
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		if ($this->isCsrfTokenValid('delete' . $comment->getId(), $data['_token'])) {
			$idComment = $comment->getId();
			$em->remove($comment);
			$em->flush();
			return new JsonResponse(['success' => $idComment]);
		}
		return new JsonResponse(['error' => 'invalid_token'], 400);
	}
}