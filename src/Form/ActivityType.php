<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Participant;
use App\Entity\Place;
use App\Entity\Site;
use App\Entity\State;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de la sortie',
            ])
            ->add('dateStartTime', null, [
                'widget' => 'single_text',
                'label' => 'Date et heure de début',
            ])
            ->add('duration', null, [
                'label' => 'Durée (en minutes)',
            ])
            ->add('registrationDeadLine', null, [
                'widget' => 'single_text',
                'label' => 'Date limite d\'inscription',
            ])
            ->add('maxRegistration', null, [
                'label' => 'Nombre maximum d\'inscriptions',
            ])
            ->add('description', null, [
                'label' => 'Description',
            ])
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'name',
                'label' => 'Site',
                'placeholder' => 'Choisissez un site',
            ])
            ->add('place', EntityType::class, [
                'class' => Place::class,
                'choice_label' => 'name',
                'label' => 'Lieu',
                'placeholder' => 'Choisissez un lieu',
                'required' => true,
            ])
            ->add('state', EntityType::class, [
                'class' => State::class,
                'choice_label' => 'name',
                'label' => 'État de la sortie',
                'placeholder' => 'Sélectionnez un état'
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
