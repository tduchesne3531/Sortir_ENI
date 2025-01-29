<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Site;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'label' => false,
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                    ],
                ],
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
//                        new PasswordStrength(),
//                        new NotCompromisedPassword(),
                    ],
                    'label' => 'Mot de passe',
                    'label_attr' => ['class' => 'block text-gray-700 font-semibold mb-1'],
                    'attr' => ['class' => 'w-full border-gray-300 rounded-lg focus:ring-cyan-500 focus:border-cyan-500 p-2'],
                ],
                'second_options' => [
                    'label' => 'Confirmation de mot de passe',
                    'label_attr' => ['class' => 'block text-gray-700 font-semibold mb-1'],
                    'attr' => ['class' => 'w-full border-gray-300 rounded-lg focus:ring-cyan-500 focus:border-cyan-500 p-2'],
                ],
                'invalid_message' => 'The password fields must match.',
                'mapped' => false,
            ])
            ->add('isAdmin', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Administrator',
            ])
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'name',
                'label' => 'Site de rattachement',
            ])
            ->add('photo', FileType::class, [
                'mapped' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG, JPG, PNG, GIF).',
                        'maxSizeMessage' => 'Max file size 5 MB',
                    ]),
                ],
                'label' => 'Photo de profil',
                'required' => false,
            ])
        ;
        if ($options['is_edit_mode'] ?? false) {
            $builder
                ->add('plainPassword', RepeatedType::class, [
                    'label' => false,
                    'type' => PasswordType::class,
                    'options' => [
                        'attr' => [
                            'autocomplete' => 'new-password',
                        ],
                    ],
                    'first_options' => [
                        'constraints' => [
                            new Length([
                                'min' => 6,
                                'minMessage' => 'Your password should be at least {{ limit }} characters',
                                // max length allowed by Symfony for security reasons
                                'max' => 4096,
                            ]),
//                        new PasswordStrength(),
//                        new NotCompromisedPassword(),
                        ],
                        'label' => 'Mot de passe',
                        'label_attr' => ['class' => 'block text-gray-700 font-semibold mb-1'],
                        'attr' => ['class' => 'w-full border-gray-300 rounded-lg focus:ring-cyan-500 focus:border-cyan-500 p-2'],
                    ],
                    'second_options' => [
                        'label' => 'Confirmation de mot de passe',
                        'label_attr' => ['class' => 'block text-gray-700 font-semibold mb-1'],
                        'attr' => ['class' => 'w-full border-gray-300 rounded-lg focus:ring-cyan-500 focus:border-cyan-500 p-2'],
                    ],
                    'invalid_message' => 'The password fields must match.',
                    'mapped' => false,
                    'required' => false,
                ]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
            'is_edit_mode' => false,
        ]);
    }
}
