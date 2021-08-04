<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('title')
			->add('category', EntityType::class, [
				'class' => Category::class,
				'choice_label' => 'name',
				'multiple' => false
			])
			->add('description')
			->add('images', FileType::class, [
				'label' => false,
				'multiple' => true,
				'mapped' => false,
				'required' => false
			]);
	}
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class'         => Trick::class,
			'translation_domain' => 'forms'
		]);
	}
}
