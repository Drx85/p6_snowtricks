<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
	/**
	 * @var TrickRepository
	 */
	private TrickRepository $trickRepository;
	/**
	 * @var UserRepository
	 */
	private UserRepository $userRepository;
	
	public function __construct(TrickRepository $trickRepository, UserRepository $userRepository)
	{
		$this->trickRepository = $trickRepository;
		$this->userRepository = $userRepository;
	}
	
	public function load(ObjectManager $manager)
    {
		$faker = Factory::create('fr_FR');
		$tricks = $this->trickRepository->findAll();
		$users = $this->userRepository->findAll();
		foreach ($tricks as $trick) {
			for ($i = 0; $i < mt_rand(0, 15); $i++) {
				$comment = new Comment();
				$comment->setMessage($faker->sentences(2, true))
					->setUser($users[mt_rand(0, 9)])
					->setTrick($trick);
				$manager->persist($comment);
			}
		}
		$manager->flush();
    }
	
	public function getDependencies()
	{
		return [
			TrickFixtures::class,
			UserFixtures::class
		];
	}
}
