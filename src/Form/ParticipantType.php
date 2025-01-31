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
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints as Assert;


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
                            'message' => 'Entrez un mot de passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Le mot de passe ne doit pas contenir moins de {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                        // Vérifie que le password n'est pas compromis
                        new NotCompromisedPassword([
                            'message' => 'Ce mot de passe a été compromis dans une fuite de données. Veuillez en choisir un autre.',
                        ]),
                        new Assert\Regex([
                            // Ajout d'une regex pour obliger l'utilisation d'une majuscule, d'une minuscule, d'un chiffre et d'un caractère spécial
                            'pattern' => "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
                            'message' => 'Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.',
                        ]),
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
                'invalid_message' => 'Les mots de passe doivent correspondre.',
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
