<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
	public function load(ObjectManager $manager)
	{
		$categories = array('Grab', 'Rotation', 'Flip', 'Slide', 'Old School', 'Rotation désaxée', 'Autres');
		foreach ($categories as $name) {
			$category = new Category();
			$category->setName($name);
			$manager->persist($category);
		}
		$manager->flush();
	}
}