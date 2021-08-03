<?php


namespace App\Controller\Publish;


use App\Entity\Trick;
use App\Form\TrickType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublishTrickController extends AbstractController
{
	/**
	 * @Route("/publish/trick/create", name="publish.trick.new")
	 * @param Request                $request
	 *
	 * @param EntityManagerInterface $em
	 *
	 * @return Response
	 */
	public function new(Request $request, EntityManagerInterface $em)
	{
		$trick = new Trick();
		$form = $this->createForm(TrickType::class, $trick);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($trick);
			$em->flush();
			$this->addFlash('success', 'Bien créé avec succès.');
			return $this->redirectToRoute('home');
		}
		
		return $this->render('publish/new.html.twig', [
			'trick' => $trick,
			'form'  => $form->createView()
		]);
	}
}