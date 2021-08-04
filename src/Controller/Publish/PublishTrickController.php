<?php


namespace App\Controller\Publish;


use App\Entity\Image;
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
			} else {
				$file = 'default.jpg';
			}
			$trick->setHeaderImage($file);
			$em->persist($trick);
			$em->flush();
			$this->addFlash('success', 'Figure créée avec succès.');
			return $this->redirectToRoute('home');
		}
		
		return $this->render('publish/new.html.twig', [
			'trick' => $trick,
			'form'  => $form->createView()
		]);
	}
}