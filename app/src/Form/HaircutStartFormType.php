<?php

namespace App\Form;

use App\Entity\Barber;
use App\Entity\CutType;
use App\Entity\Haircut;
use App\Enumerations\CutEnumeration;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HaircutStartFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'barbername',
                EntityType::class,
                [
                    'class' => Barber::class,
                    'choice_label' => 'name',
                    'choice_value' => 'name',
                    'multiple' => false,
                    'expanded' => false,
                    'label' => "Barber Assigned: "
                ]

            )
            ->add(
                'cuttype',
                EntityType::class,
                [
                    'class' => CutType::class,
                    'choice_label' => 'type',
                    'choice_value' => 'type',
                    'multiple' => false,
                    'expanded' => false,
                    'label' => "Cut Type: "
                ]

            )
            ->add(
                'Submit',
                SubmitType::class
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Haircut::class,
        ]);
    }
}
