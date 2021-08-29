<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DeleteFixtures extends Fixture implements DependentFixtureInterface
{
	private ParameterBagInterface $params;
	
	public function __construct(ParameterBagInterface $params) {
		$this->params = $params;
	}
	
	public function load(ObjectManager $manager)
	{
		$this->removeDirectory($this->params->get('fixtures_directory'));
	}
	
	private function removeDirectory($path)
	{
		$files = glob($path . '/*');
		foreach ($files as $file) {
			is_dir($file) ? $this->removeDirectory($file) : unlink($file);
		}
		rmdir($path);
	}
	
	public function getDependencies(): array
	{
		return [
			TrickFixtures::class,
			CategoryFixtures::class,
			CommentFixtures::class,
			ImageFixtures::class,
			UserFixtures::class,
			VideoFixtures::class
		];
	}
}