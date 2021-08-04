<?php


namespace App\Controller;


use App\Entity\Trick;
use App\Repository\TrickRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class TrickController extends AbstractController
{
	/**
	 * @var TrickRepository
	 */
	private TrickRepository $repository;
	/**
	 * @var
	 */
	private $em;
	
	public function __construct(TrickRepository $repository)
	{
		$this->repository = $repository;
	}
	
	/**
	 * @Route("/tricks", name="trick.index")
	 * @return Response
	 */
	public function index(): Response
	{
		return $this->render('trick/index.html.twig');
	}
	
	/**
	 * @Route("/tricks/{slug}-{id}", name="trick.show", requirements={"slug": "[a-z0-9\-]*"})
	 * @param Trick  $trick
	 *
	 * @param string $slug
	 *
	 * @return Response
	 */
	public function show(Trick $trick, string $slug): Response
	{
		if ($trick->getSlug() !== $slug) {
			return $this->redirectToRoute('trick.show', [
				'id' => $trick->getId(),
				'slug' => $trick->getSlug()
			], 301);
		}
		return $this->render('trick/show.html.twig', compact('trick'));
	}
	
}