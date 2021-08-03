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
			$trick->setUpdatedAt(new \DateTimeImmutable());
			$this->em->flush();
			$this->addFlash('success', 'Bien modifié avec succès');
			return $this->redirectToRoute('admin.trick.index');
		}
		
		return $this->render('admin/trick/edit.html.twig', [
			'trick' => $trick,
			'form'  => $form->createView()
		]);
	}
	
	/**
	 * @Route("/admin/delete/trick/{id}", name="admin.trick.delete")
	 * @param Trick $trick
	 *
	 * @return RedirectResponse
	 */
	public function delete(Trick $trick, Request $request)
	{
		if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->get('_token'))) {
			$this->em->remove($trick);
			$this->em->flush();
			$this->addFlash('success', 'Bien supprimé avec succès');
		}
		return $this->redirectToRoute('admin.trick.index');
	}
}