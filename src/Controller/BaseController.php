<?php


namespace App\Controller;


use App\Entity\Image;
use App\Entity\Video;
use App\Service\EmbedYoutubeUrl;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
	/**
	 * @param $images
	 * @param $trick
	 *
	 * @return mixed
	 */
	protected function addImage($images, $trick)
	{
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
	}
	
	protected function addHeaderImage($headerImage)
	{
		$file = md5(uniqid()) . '.' . $headerImage->guessExtension();
		$headerImage->move(
			$this->getParameter('header_directory'),
			$file
		);
		return $file;
	}
	
	protected function addVideo($urls, $trick)
	{
		$urls = explode(",", $urls);
		$parser = new EmbedYoutubeUrl();
		foreach ($urls as $url) {
			$url = $parser->embedLink($url);
			$video = new Video();
			$video->setLink($url);
			$trick->addVideo($video);
		}
		
	}
}