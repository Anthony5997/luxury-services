<?php

namespace App\Form;

use App\Entity\JobOffer;
use App\Entity\JobCategory;
use App\Entity\JobType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class JobOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('jobTitle')
            ->add('description')
            ->add('location')
            ->add('closingDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('salary')
            ->add('jobCategory', EntityType::class, [
                'class' => JobCategory::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.category', 'ASC');
                },
                'choice_label' => 'category',
            ])
            ->add('jobType', EntityType::class, [
                'class' => JobType::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.type', 'ASC');
                },
                'choice_label' => 'type',
            ])
            ->add('startingDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JobOffer::class,
        ]);
    }
}
