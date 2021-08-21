<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
	private UserPasswordHasherInterface $passwordHasher;
	
	public function __construct(UserPasswordHasherInterface $passwordHasher)
	{
		$this->passwordHasher = $passwordHasher;
	}
	
	public function load(ObjectManager $manager)
	{
		$faker = Factory::create('fr_FR');
		for ($i = 0; $i < 10; $i++) {
			$user = new User();
			$user->setUsername($faker->userName)
				->setEmail($faker->email)
				->setRoles((array)'ROLE_USER')
				->setIsVerified(true)
				->setPassword($this->passwordHasher->hashPassword($user, 'demo'));
			$manager->persist($user);
		}
		//Create 1 admin account, you can set your username, mail, and password
		$user = new User();
		$user->setUsername('admin')
			->setEmail('your-email@gmail.com')
			->setRoles((array)'ROLE_ADMIN')
			->setIsVerified(true)
			->setPassword($this->passwordHasher->hashPassword($user, 'demo'));
		$manager->persist($user);
		$manager->flush();
	}
}
