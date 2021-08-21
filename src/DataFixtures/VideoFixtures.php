<?php

namespace App\DataFixtures;

use App\Entity\Video;
use App\Repository\TrickRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VideoFixtures extends Fixture implements DependentFixtureInterface
{
	/**
	 * @var TrickRepository
	 */
	private TrickRepository $trickRepository;
	
	public function __construct(TrickRepository $trickRepository)
	{
		$this->trickRepository = $trickRepository;
	}
	
	public function load(ObjectManager $manager)
	{
		$videos = array(
			'https://www.youtube.com/embed/jm19nEvmZgM',
			'https://www.dailymotion.com/embed/video/x5fizx',
			'https://www.youtube.com/embed/KEdFwJ4SWq4',
			'https://www.youtube.com/embed/gZFWW4Vus-Q',
			'https://www.youtube.com/embed/_Qq-YoXwNQY',
			'https://www.youtube.com/embed/ATMiAVTLsuc',
			'https://www.dailymotion.com/embed/video/x6wvopf',
			'https://www.youtube.com/embed/GS9MMT_bNn8',
			'https://www.dailymotion.com/embed/video/x2n7xl2',
			'https://www.youtube.com/embed/eGJ8keB1-JM',
			'https://www.dailymotion.com/embed/video/xv41jc',
			'https://www.youtube.com/embed/SlhGVnFPTDE',
			'https://www.dailymotion.com/embed/video/x52wcou',
			'https://www.youtube.com/embed/gO5GLk7oQhU',
			'https://www.dailymotion.com/embed/video/x3rq6ol',
			'https://www.youtube.com/embed/4IVdWdvsrVA',
			'https://www.youtube.com/embed/6tgjY8baFT0'
		);
		$tricks = $this->trickRepository->findAll();
		foreach ($tricks as $trick) {
			for ($i = 0; $i < mt_rand(2, 3); $i++) {
				$link = $videos[mt_rand(0, 16)];
				$video = new Video();
				$video->setTrick($trick)
					->setLink($link);
				$manager->persist($video);
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
