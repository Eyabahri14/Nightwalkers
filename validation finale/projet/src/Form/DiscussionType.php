<?php

namespace App\Form;

use App\Entity\Discussion;
use App\Entity\Thematique;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiscussionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('date_creation_disc',DateType::class)
            ->add('titre')
            ->add('thematique' , EntityType::class,[
                'class'=>Thematique::class,
                'choice_label'=>'nom'
            ])
            ->add('Submit',SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
