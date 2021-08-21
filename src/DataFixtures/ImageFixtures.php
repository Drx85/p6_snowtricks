<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Repository\TrickRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageFixtures extends Fixture implements DependentFixtureInterface
{
	/**
	 * @var TrickRepository
	 */
	private TrickRepository $trickRepository;
	/**
	 * @var ParameterBagInterface
	 */
	private ParameterBagInterface $params;
	
	public function __construct(TrickRepository $trickRepository, ParameterBagInterface $params)
	{
		$this->trickRepository = $trickRepository;
		$this->params = $params;
	}
	
	public function load(ObjectManager $manager)
	{
		$tricks = $this->trickRepository->findAll();
		
		foreach ($tricks as $trick) {
			for ($i = 0; $i < mt_rand(1, 3); $i++) {
				$file = mt_rand(1, 23) . '.jpg';
				$image = new Image();
				$image->setTrick($trick)
					->setName($file);
				$manager->persist($image);
				
				copy($this->params->get('image_fixtures_directory') . '/' . $file, $this->params->get('image_fixtures_directory') . '/copy-' . $file,);
				
				$headerImage = new UploadedFile($this->params->get('image_fixtures_directory') . '/copy-' . $file, 'Image', null, null, true, true);
				
				$headerImage->move(
					$this->params->get('images_directory'),
					$file
				);
			}
		}
		$manager->flush();
	}
	
	public function getDependencies()
	{
		return [
			TrickFixtures::class,
		];
	}
}
