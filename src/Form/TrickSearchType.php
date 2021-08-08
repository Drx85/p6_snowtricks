<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\TrickSearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categories', EntityType::class, [
            	'required' => false,
				'label' => false,
				'class' => Category::class,
				'choice_label' => 'name',
				'multiple' => true
			]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickSearch::class,
			'method' => 'get',
			'csrf_protection' => false
        ]);
    }
    
    public function getBlockPrefix()
	{
		return '';
	}
}
