<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
	/**
	 * @param TrickRepository        $repository
	 *
	 * @param Request                $request
	 *
	 * @param EntityManagerInterface $em
	 *
	 * @return Response
	 * @Route("/", name="home")
	 */
	public function index(TrickRepository $repository, Request $request, EntityManagerInterface $em): Response
	{
		$offset = max(TrickRepository::PAGINATOR_PER_PAGE, $request->query->getInt('offset', TrickRepository::PAGINATOR_PER_PAGE));
		
		$paginator = $repository->getTrickPaginator($offset);
		
		$tricks = $paginator;
		return $this->render('pages/home.html.twig',[
			'tricks' => $tricks,
			'next' => $offset+TrickRepository::PAGINATOR_PER_PAGE
		]);
	}
	
}