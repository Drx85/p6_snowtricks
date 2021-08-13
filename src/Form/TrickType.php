<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Url;

class TrickType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('title')
			->add('category', EntityType::class, [
				'class'        => Category::class,
				'choice_label' => 'name',
				'multiple'     => false
			])
			->add('description')
			->add('videos', TextAreaType::class, [
				'mapped' => false,
				'required' => false,
				'label' => 'Ajouter des liens vidéos YouTube ou Dailymotion (séparés par une virgule, sans espace)',
				'constraints' => [
					new Url()
				]
			])
			->add('headerImage', FileType::class, [
				'required'    => false,
				'mapped'      => false,
				'label'       => 'Image à la une (1920*900 pixels, max 1000 ko)',
				'constraints' => [
					new Image([
						'maxHeight' => 900,
						'minHeight' => 900,
						'maxWidth'  => 1920,
						'minWidth'  => 1920
					]),
					new File([
						'maxSize' => '1000k',
					])
				],

			])
			->add('images', FileType::class, [
				'label'    => 'Miniatures (400*250 pixels, max 200 ko)',
				'multiple' => true,
				'mapped'   => false,
				'required' => false,
				'constraints' => [
					new All([
					new Image([
						'maxHeight' => 250,
						'minHeight' => 250,
						'maxWidth'  => 400,
						'minWidth'  => 400
					]),
					new File([
						'maxSize' => '200k',
					])
						]
					)
				],
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
