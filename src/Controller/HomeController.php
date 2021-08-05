<?php


namespace App\Controller;


use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
	/**
	 * @param TrickRepository $repository
	 *
	 * @param Request         $request
	 *
	 * @return Response
	 * @Route("/", name="home")
	 */
	public function index(TrickRepository $repository, Request $request): Response
	{
		$offset = max(0, $request->query->getInt('offset', 0));
		$paginator = $repository->getTrickPaginator($offset);
		
		$tricks = $paginator;
		return $this->render('pages/home.html.twig',[
			'tricks' => $tricks,
			'next' => min(count($paginator), $offset + TrickRepository::PAGINATOR_PER_PAGE),
		]);
	}
	
}