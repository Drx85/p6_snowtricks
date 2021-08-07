<?php


namespace App\Controller\Publish;


use App\Controller\BaseController;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\TrickType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublishTrickController extends BaseController
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
}