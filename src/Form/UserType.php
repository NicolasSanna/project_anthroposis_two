<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Type;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => ['class' => 'Form-component-input', 'maxlength' => 255],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le champ doit contenir moins de {{ limit }} caractères',
                    ]),
                    new Type([
                        'type' => 'string',
                        'message' => 'Le champ doit être un texte.',
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'attr' => ['class' => 'Form-component-input', 'maxlength' => 255],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le champ doit contenir moins de {{ limit }} caractères',
                    ]),
                    new Type([
                        'type' => 'string',
                        'message' => 'Le champ doit être un texte.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => ['class' => 'Form-component-input', 'maxlength' => 255],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le champ doit contenir moins de {{ limit }} caractères',
                    ]),
                    new Email([
                        'message' => 'L\'adresse email "{{ value }}" n\'est pas valide.',
                    ]),
                ],
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'required' => true,
                'attr' => ['class' => 'Form-component-input', 'maxlength' => 255],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide',
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'Le champ doit contenir moins de {{ limit }} caractères',
                    ]),
                    new Type([
                        'type' => 'string',
                        'message' => 'Le champ doit être un texte.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}