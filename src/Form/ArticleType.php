<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Type;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => true,
                'attr' => ['class' => 'Form-component-input', 'maxlength' => 255],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide',
                    ]),
                    new Length([
                        'min' => 6,
                        'max' => 255,
                        'minMessage' => 'le champ doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le champ doit contenir moins de {{ limit }} caractères',
                    ]),
                    new Type([
                        'type' => 'string',
                        'message' => 'Le champ doit être un texte.',
                    ]),
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'Form-component-input', 'maxlength' => 255],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide',
                    ]),
                    new Length([
                        'min' => 6,
                        'max' => 255,
                        'minMessage' => 'le champ doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le champ doit contenir moins de {{ limit }} caractères',
                    ]),
                    new Type([
                        'type' => 'string',
                        'message' => 'Le champ doit être un texte.',
                    ]),
                ],
            ])
            ->add('content', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => 'Form-component-input', 'rows' => 10],
                'label' => 'Contenu',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne doit pas être vide',
                    ]),
                    new Length([
                        'min' => 6,
                        'max' => 10000,
                        'minMessage' => 'le champ doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le champ doit contenir moins de {{ limit }} caractères',
                    ]),
                    new Type([
                        'type' => 'string',
                        'message' => 'Le champ doit être un texte.',
                    ]),
                ],
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'attr' => ['class' => 'Form-component-input'],
                'constraints' => [
                    new File([
                        'maxSize' => '2000k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Le site accepte uniquement les fichiers PNG et JPG',
                    ])
                ],
                'label' => 'Image',
                'data_class' => null,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'multiple' => true,
                'expanded' => false,
                'choice_label' => 'label',
                'label' => 'Catégorie',
                'attr' => ['class' => 'Form-component-input'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}