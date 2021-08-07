<?php


namespace App\Form\user;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;

class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('picture', FileType::class, [
				'label'       => 'L\'image doit avoir une taille de 150*150 pixels et sont poids ne doit pas excéder 100 ko.',
				'required'    => false,
				'mapped'      => false,
				'multiple'    => false,
				'constraints' => [
					new Image([
						'maxHeight' => 150,
						'minHeight' => 150,
						'maxWidth'  => 150,
						'minWidth'  => 150
					]),
					new File([
							'maxSize' => '100k',
						]
					)
				],
			]);
	}
	
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => User::class,
		]);
	}
}