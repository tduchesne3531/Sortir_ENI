<?php

namespace App\Form;

use App\Entity\Site;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ActivityFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Rechercher par nom'],
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'Tous les sites',
            ])
            ->add('participation', ChoiceType::class, [
                'choices' => [
                    'Mes sorties créées' => 'created',
                    'Inscrit' => 'participating',
                    'Non inscrit' => 'not_participating',
                    'Activités terminées' => 'completed',
                ],
                'required' => false,
                'placeholder' => 'Toutes les sorties',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filter',
            ]);
    }
}
