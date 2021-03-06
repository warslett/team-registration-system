<?php

namespace App\Form\Team;

use App\Entity\Hike;
use App\Entity\Team;
use App\Repository\HikeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamCreateType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('hike', EntityType::class, [
                'class' => Hike::class,
                'query_builder' => function (HikeRepository $repo) {
                    $now = new \DateTime();

                    return $repo->createQueryBuilder('h')
                        ->leftJoin('h.event', 'e')
                        ->where('e.registrationOpens < :minRegistrationOpen')
                        ->setParameter(':minRegistrationOpen', $now)
                        ->andWhere('e.registrationCloses > :maxRegistrationCloses')
                        ->setParameter(':maxRegistrationCloses', $now)
                    ;
                }
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Team::class
        ]);
    }
}
