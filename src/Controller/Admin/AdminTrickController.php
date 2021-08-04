<?php


namespace App\Controller\Admin;


use App\Entity\Image;
use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTrickController extends AbstractController
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
	 * @Route("/admin", name="admin.trick.index")
	 * @return Response
	 */
	public function index()
	{
		$tricks = $this->repository->findAll();
		return $this->render('admin/trick/index.html.twig', compact('tricks'));
	}
	
	/**
	 * @Route("/admin/edit/trick/{id}", name="admin.trick.edit")
	 * @param Trick   $trick
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function edit(Trick $trick, Request $request)
	{
		$form = $this->createForm(TrickType::class, $trick);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$images = $form->get('images')->getData();
			foreach ($images as $image) {
				$file = md5(uniqid()) . '.' . $image->guessExtension();
				$image->move(
					$this->getParameter('images_directory'),
					$file
				);
				$img = new Image();
				$img->setName($file);
				$trick->addImage($img);
			}
			$headerImage = $form->get('headerImage')->getData();
			if ($headerImage) {
				$file = md5(uniqid()) . '.' . $headerImage->guessExtension();
				$headerImage->move(
					$this->getParameter('header_directory'),
					$file
				);
				$trick->setHeaderImage($file);
			}
			$trick->setUpdatedAt(new \DateTimeImmutable());
			$this->em->flush();
			$this->addFlash('success', 'Figure modifiée avec succès.');
			return $this->redirectToRoute('home');
		}
		
		return $this->render('admin/trick/edit.html.twig', [
			'trick' => $trick,
			'form'  => $form->createView()
		]);
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
}