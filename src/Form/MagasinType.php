<?php

namespace App\Form;

use App\Entity\Magasin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;

class MagasinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du magasin',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le nom du magasin'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom du magasin est obligatoire'
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'exemple@magasin.com'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'email est obligatoire'
                    ]),
                    new Email([
                        'message' => 'Veuillez entrer une adresse email valide'
                    ])
                ]
            ])
            ->add('place', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez l\'adresse du magasin'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'adresse est obligatoire'
                    ])
                ]
            ])
            ->add('tel', TelType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '12345678'
                ],
                'constraints' => [
                    new Length([
                        'max' => 8,
                        'maxMessage' => 'Le numéro de téléphone ne peut pas dépasser {{ limit }} chiffres'
                    ]),
                    new Regex([
                        'pattern' => '/^[0-9]*$/',
                        'message' => 'Le numéro de téléphone doit contenir uniquement des chiffres'
                    ])
                ]
            ])
            ->add('owner', TextType::class, [
                'label' => 'Propriétaire',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom du propriétaire'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Magasin::class,
            'csrf_protection' => true, // Add this
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'magasin_item', // Add unique token ID
        ]);
    }
}