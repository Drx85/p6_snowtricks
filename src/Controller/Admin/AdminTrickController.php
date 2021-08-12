<?php


namespace App\Controller\Admin;


use App\Controller\BaseController;
use App\Entity\Trick;
use App\Entity\Image;
use App\Entity\Video;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTrickController extends BaseController
{
	/**
	 * @var TrickRepository
	 */
	private TrickRepository $repository;
	/**
	 * @var ObjectManager
	 */
	private $em;
	
	public function __construct(TrickRepository $repository, EntityManagerInterface $em)
	{
		$this->repository = $repository;
		$this->em = $em;
	}
	
	/**
	 * @Route("/admin/delete/trick/{id}", name="admin.trick.delete")
	 * @param Trick   $trick
	 *
	 * @param Request $request
	 *
	 * @return RedirectResponse
	 */
	public function delete(Trick $trick, Request $request)
	{
		if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->get('_token'))) {
			$this->em->remove($trick);
			$this->em->flush();
			$this->addFlash('success', 'Figure supprimée avec succès.');
		}
		return $this->redirectToRoute('home');
	}
	
	/**
	 * @Route("/admin/delete/image/{id}", name="admin.image.delete", methods={"DELETE"})
	 * @param Image   $image
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function deleteImage(Image $image, Request $request)
	{
		$data = json_decode($request->getContent(), true);
		if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
			$name = $image->getName();
			unlink($this->getParameter('images_directory') . '/' . $name);
			$this->em->remove($image);
			$this->em->flush();
			return new JsonResponse(['success' => 1]);
		}
		return new JsonResponse(['error' => 'invalid_token'], 400);
	}
	
	/**
	 * @Route("/admin/delete/video/{id}", name="admin.video.delete", methods={"DELETE"})
	 * @param Video   $video
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function deleteVideo(Video $video, Request $request)
	{
		$data = json_decode($request->getContent(), true);
		if ($this->isCsrfTokenValid('delete' . $video->getId(), $data['_token'])) {
			$this->em->remove($video);
			$this->em->flush();
			return new JsonResponse(['success' => 1]);
		}
		return new JsonResponse(['error' => 'invalid_token'], 400);
	}
}