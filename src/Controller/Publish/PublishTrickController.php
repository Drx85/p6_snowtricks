<?php


namespace App\Controller\Publish;


use App\Controller\BaseController;
use App\Entity\Trick;
use App\Form\TrickType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class PublishTrickController extends BaseController
{
	/**
	 * @Route("/publish/trick/create", name="publish.trick.new")
	 * @param Request                $request
	 *
	 * @param EntityManagerInterface $em
	 *
	 * @param UserInterface          $user
	 *
	 * @return Response
	 */
	public function new(Request $request, EntityManagerInterface $em, UserInterface $user)
	{
		$trick = new Trick();
		$form = $this->createForm(TrickType::class, $trick);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$images = $form->get('images')->getData();
			$this->addImage($images, $trick);
			$headerImage = $form->get('headerImage')->getData();
			if ($headerImage) {
				$file = $this->addHeaderImage($headerImage);
			} else {
				$file = 'default.jpg';
			}
			$trick->setHeaderImage($file);
			$videoLink = $form->get('videos')->getData();
			if ($videoLink) $this->addVideo($videoLink, $trick);
			$trick->setUser($user);
			$em->persist($trick);
			$em->flush();
			$this->addFlash('success', 'Figure créée avec succès.');
			return $this->redirectToRoute('home');
		}
		return $this->render('publish/new_trick.html.twig', [
			'trick' => $trick,
			'form'  => $form->createView()
		]);
	}
	
	/**
	 * @Route("/publish/edit/trick/{id}", name="publish.trick.edit")
	 * @param Trick   $trick
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function edit(Trick $trick, Request $request)
	{
		$this->denyAccessUnlessGranted('trick_edit', $trick);
		$form = $this->createForm(TrickType::class, $trick);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$images = $form->get('images')->getData();
			$this->addImage($images, $trick);
			$headerImage = $form->get('headerImage')->getData();
			if ($headerImage) {
				$file = $this->addHeaderImage($headerImage);
				$trick->setHeaderImage($file);
			}
			$videoLink = $form->get('videos')->getData();
			if ($videoLink) $this->addVideo($videoLink, $trick);
			$this->em->flush();
			$this->addFlash('success', 'Figure modifiée avec succès.');
			return $this->redirectToRoute('home');
		}
		return $this->render('publish/edit.html.twig', [
			'trick' => $trick,
			'form'  => $form->createView()
		]);
	}
}