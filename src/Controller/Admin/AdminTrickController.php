<?php


namespace App\Controller\Admin;


use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTrickController extends AbstractController
{
	/**
	 * @var TrickRepository
	 */
	private $repository;
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
	 * @Route("/admin/trick/create", name="admin.trick.new")
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function new(Request $request)
	{
		$trick = new Trick();
		$form = $this->createForm(TrickType::class, $trick);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$this->em->persist($trick);
			$this->em->flush();
			return $this->redirectToRoute('admin.trick.index');
		}
		
		return $this->render('admin/trick/new.html.twig', [
			'trick' => $trick,
			'form' => $form->createView()
		]);
	}
	
	/**
	 * @Route("/admin/trick/{id}", name="admin.trick.edit")
	 * @param Trick        $trick
	 * @param RequestStack $request
	 *
	 * @return Response
	 */
	public function edit(Trick $trick, Request $request)
	{
		$form = $this->createForm(TrickType::class, $trick);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$this->em->flush();
			return $this->redirectToRoute('admin.trick.index');
		}
		
		return $this->render('admin/trick/edit.html.twig', [
			'trick' => $trick,
			'form' => $form->createView()
		]);
	}
}