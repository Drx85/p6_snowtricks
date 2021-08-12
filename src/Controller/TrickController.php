<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
	/**
	 * @var CommentRepository
	 */
	private CommentRepository $repository;
	
	public function __construct(CommentRepository $repository)
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
				'id'   => $trick->getId(),
				'slug' => $trick->getSlug()
			], 301);
		}
		$comment = new Comment();
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
		$comments = $this->repository->getFirstComments($trick->getId());
		return $this->render('trick/show.html.twig', [
			'trick'    => $trick,
			'form'     => $form->createView(),
			'comments' => $comments,
		]);
	}
	
	/**
	 * @Route("/tricks/{id}/comments", name="trick.comments.show", methods={"POST"})
	 * @param Request $request
	 *
	 * @param Trick   $trick
	 *
	 * @return JsonResponse
	 */
	public function loadComments(Request $request, Trick $trick)
	{
		$data = json_decode($request->getContent(), true);
		$offset = max(CommentRepository::PAGINATOR_PER_PAGE, $data['offset']);
		$paginator = $this->repository->getCommentPaginator($offset, $trick->getId());
		$i = 0;
		foreach ($paginator as $comment) {
			$usernames[$i] = $comment->getUser()->getUserIdentifier();
			$userPictures[$i] = $comment->getUser()->getPicture();
			$i++;
		}
		$paginator = $paginator->getQuery()->getArrayResult();
		$array_size = $this->repository::PAGINATOR_PER_PAGE;
		if(count($paginator) < $this->repository::PAGINATOR_PER_PAGE) $array_size = count($paginator);
		for ($i = 0; $i < $array_size; $i++) {
			array_push($paginator[$i], $usernames[$i]);
			array_push($paginator[$i], $userPictures[$i]);
			
			$timeStamp = date_timestamp_get($paginator[$i]['created_at']);
			$paginator[$i]['created_at'] = $timeStamp;
			
			$paginator[$i]['username'] = $paginator[$i][0];
			unset($paginator[$i][0]);
			$paginator[$i]['userPicture'] = $paginator[$i][1];
			unset($paginator[$i][1]);
		}
		return new JsonResponse(['comments' => $paginator]);
	}
}