<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Commentaire',
                'attr' => ['class' => 'Form-component-input', 'maxlength' => 255],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide',
                    ]),
                    new Length([
                        'min' => 6,
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                        'minMessage' => 'le champ doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le champ doit contenir moins de {{ limit }} caractères',
                    ]),
                    new Type([
                        'type' => 'string',
                        'message' => 'Le champ doit être un texte.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
