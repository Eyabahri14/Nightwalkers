<?php

namespace App\Form;

use App\Entity\Tutorial;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TutorialFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('dateTuto',DateTimeType::class,[
                'label'=>'Tutorial Date'
            ])
            ->add('category',ChoiceType::class,[
                'choices' => [
                    'Pick Category' => 'Pick Category',
                    'Starters'=>'Starters',
                    'Main Dishes'=>'Main Dishes',
                    'Side Dishes'=>'Side Dished',
                    'Deserts'=>'Deserts',
                ],
                'expanded'=>false,
                'multiple'=>false])

            ->add('titre',TextType::class,[
                'label'=>'Title',
                'attr' => [
                    'placeholder' => 'Title',
                ]])
            ->add('description',TextAreaType::class,[
                'label'=>'Description',
                'attr' => [
                    'placeholder' => 'Description',
                ]])
            ->add('prix',NumberType::class,[
                'label'=>'Price',
                'attr' => [
                    'placeholder' => 'Price',
                ]])
            ->add('video',FileType::class,[
                'label' => 'Tutorial (video file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '150Mi',
                        'mimeTypes' => [
                            'video/mp4',

                        ],
                        'mimeTypesMessage' => 'Please upload a valid video file',
                    ])
                ],
            ])

            ->add('image',FileType::class,[
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2Mi',
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tutorial::class,
        ]);
    }
}
