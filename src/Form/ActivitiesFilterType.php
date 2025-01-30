<?php

namespace App\Form;

use App\Entity\Site;
use App\dto\ActivityFilter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivitiesFilterType extends AbstractType
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $sites = $this->entityManager->getRepository(Site::class)->findAll();
        $choices = ['Tous les sites' => null];
        foreach ($sites as $site) {
            $choices[$site->getName()] = $site;
        }
        $builder
            ->add('site', ChoiceType::class, [
                'choices' => $choices,
                'required' => false,
                'placeholder' => "Sélectionner un site",
                'label' => 'Site',
            ])
            ->add('search', SearchType::class, [
                'required' => false,
                'label' => 'Recherche',
                'attr' => [
                    'placeholder' => 'Rechercher...',
                ],
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date de début',
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Date de fin',
            ])
            ->add('organizer', CheckboxType::class, [
                'required' => false,
                'label' => 'Mes sorties organisées',
            ])
            ->add('registered', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties où je suis inscrit/e',
            ])
            ->add('notRegistered', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties où je ne suis pas inscrit/e',
            ])
            ->add('past', CheckboxType::class, [
                'required' => false,
                'label' => 'Sorties passées',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ActivityFilter::class,
            'method' => 'GET', // Si utilisé pour un filtre, en général on utilise GET
            'csrf_protection' => false, // Désactiver pour les filtres (optionnel)
        ]);
    }
}
