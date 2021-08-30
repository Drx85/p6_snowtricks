<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
	/**
	 * @Route("/cgu", name="cgu")
	 * @return Response
	 */
	public function indexCgu(): Response
	{
		return $this->render('pages/cgu.html.twig');
	}
	
	/**
	 * @Route("/plan-du-site", name="sitemap")
	 * @return Response
	 */
	public function indexSitemap(): Response
	{
		return $this->render('pages/sitemap.html.twig');
	}
}