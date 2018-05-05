<?php

namespace App\Form\Walker;

use App\Entity\Walker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WalkerCreateType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('foreName', TextType::class, ['label' => 'Forename'])
            ->add('surName', TextType::class, ['label' => 'Surname'])
            ->add('emergencyContactNumber', TextType::class, ['label' => 'Emergency Contact Number'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Walker::class
        ]);
    }
}
