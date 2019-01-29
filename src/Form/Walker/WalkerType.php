<?php

namespace App\Form\Walker;

use App\Entity\Walker;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WalkerType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('foreName', TextType::class, ['label' => 'Forename'])
            ->add('surName', TextType::class, ['label' => 'Surname'])
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'],
                'html5' => false,
                'format' => 'dd/MM/yyyy'
            ])
            ->add('emergencyContactNumber', TextType::class, ['label' => 'Emergency Contact Number'])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Walker::class
        ]);
    }
}
