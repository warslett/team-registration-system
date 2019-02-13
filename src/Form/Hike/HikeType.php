<?php

namespace App\Form\Hike;

use App\Entity\Hike;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HikeType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('minWalkers', IntegerType::class)
            ->add('maxWalkers', IntegerType::class)
            ->add('minAge', NumberType::class)
            ->add('maxAge', NumberType::class)
            ->add('feePerWalker', NumberType::class, [
                'label' => 'Fee Per Walker (pounds)'
            ])
            ->add('startTimeInterval', IntegerType::class, [
                'label' => 'Start Time Interval (minutes)'
            ])
            ->add('firstTeamStartTime', DateTimeType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datetimepicker'],
                'html5' => false,
                'format' => 'dd/MM/yyyy HH:mm'
            ])
            ->add('joiningInfoURL', TextType::class, [
                'label' => 'Joining Info URL (eg. https://hike.org.uk/joining-info.pdf)'
            ])
            ->add('kitListURL', TextType::class, [
                'label' => 'Kit List URL (eg. https://hike.org.uk/kitlist.pdf)'
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hike::class
        ]);
    }
}
