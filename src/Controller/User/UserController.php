<?php


namespace App\Controller\User;


use App\Form\user\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
	/**
	 * @var UserRepository
	 */
	private UserRepository $repository;
	/**
	 * @var EntityManagerInterface
	 */
	private EntityManagerInterface $em;
	
	/**
	 * @param UserRepository         $repository
	 * @param EntityManagerInterface $em
	 */
	public function __construct(UserRepository $repository, EntityManagerInterface $em)
	{
		$this->repository = $repository;
		$this->em = $em;
	}
	
	/**
	 * @Route("/user/edit/{id}", name="user.edit")
	 * @param int     $id
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function edit(int $id, Request $request, UserInterface $userSession): Response
	{
		$user = $this->repository->find($id);
		$form = $this->createForm(UserType::class, $user);
		
		$this->denyAccessUnlessGranted('user_edit', $user);
		
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$picture = $form->get('picture')->getData();
			if ($picture) {
			$file = md5(uniqid()) . '.' . $picture->guessExtension();
				$picture->move(
				$this->getParameter('user_picture_directory'), $file);
			$user->setPicture($file);
			}
			$this->em->persist($user);
			$this->em->flush();
			$this->addFlash('success', 'Profil modifié avec succès.');
			return $this->redirectToRoute('home');
		}
		
		
		$form = $form->createView();
		return $this->render('user/edit.html.twig', compact('user', 'form'));
	}
	
}