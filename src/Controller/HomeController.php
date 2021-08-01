<?php


namespace App\Controller;


use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
	/**
	 * @param TrickRepository $repository
	 *
	 * @Route("/", name="home")
	 * @return Response
	 */
	public function index(TrickRepository $repository): Response
	{
		$tricks = $repository->findAll(); dump($tricks);
		return $this->render('pages/home.html.twig', compact('tricks'));
	}
	
}