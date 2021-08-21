<?php

namespace App\DataFixtures;

use App\Repository\CategoryRepository;
use App\Entity\Trick;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
	private CategoryRepository $categoryRepository;
	private UserRepository $userRepository;
	/**
	 * @var ParameterBagInterface
	 */
	private ParameterBagInterface $params;
	
	public function __construct(CategoryRepository $categoryRepository, UserRepository $userRepository, ParameterBagInterface $params)
	{
		$this->categoryRepository = $categoryRepository;
		$this->userRepository = $userRepository;
		$this->params = $params;
	}
	
	public function load(ObjectManager $manager)
	{
		$faker = Factory::create('fr_FR');
		$categories = $this->categoryRepository->findAll();
		$users = $this->userRepository->findAll();
		
		foreach ($categories as $category) {
			for ($i = 0; $i < mt_rand(0, 5); $i++) {
				$file = mt_rand(1, 10) . '.jpg';
				$trick = new Trick();
				$trick->setCategory($category)
					->setTitle($faker->words(3, true))
					->setDescription($faker->sentences(10, true))
					->setUser($users[mt_rand(1, 10)])
					->setHeaderImage($file);
				
				$manager->persist($trick);
				
				copy($this->params->get('header_fixtures_directory') . '/' . $file, $this->params->get('header_fixtures_directory') . '/copy-' . $file,);
				
				$headerImage = new UploadedFile($this->params->get('header_fixtures_directory') . '/copy-' . $file, 'HeaderImage', null, null, true, true);
				
				$headerImage->move(
					$this->params->get('header_directory'),
					$file
				);
			}
		}
		$manager->flush();
	}
	
	public function getDependencies()
	{
		return [
			UserFixtures::class,
		];
	}
}