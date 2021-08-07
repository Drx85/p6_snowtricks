<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class TrickController extends AbstractController
{
	/**
	 * @var TrickRepository
	 */
	private TrickRepository $repository;
	
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
	 * @param Trick                  $trick
	 *
	 * @param string                 $slug
	 *
	 * @param Request                $request
	 * @param EntityManagerInterface $em
	 *
	 * @return Response
	 */
	public function show(Trick $trick, string $slug, Request $request, EntityManagerInterface $em): Response
	{
		if ($trick->getSlug() !== $slug) {
			return $this->redirectToRoute('trick.show', [
				'id' => $trick->getId(),
				'slug' => $trick->getSlug()
			], 301);
		}
		$comment = New Comment();
		$form = $this->createForm(CommentType::class, $comment);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$this->denyAccessUnlessGranted('ROLE_USER');
			$comment->setUser($this->getUser());
			$comment->setTrick($trick);
			$em->persist($comment);
			$em->flush();
			$this->addFlash('success', 'Commentaire ajouté avec succès.');
			return $this->redirectToRoute('home');
		}
		$comments = $trick->getComments();
		return $this->render('trick/show.html.twig', [
			'trick' => $trick,
			'form' => $form->createView(),
			'comments' => $comments
		]);
	}
}