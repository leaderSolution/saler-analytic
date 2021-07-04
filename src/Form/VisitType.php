<?php

namespace App\Form;

use App\Entity\Visit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class VisitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'attr' => [
                    'placeholder' => 'Title',
                ],
                
            ])
            ->add('startTime', DateTimeType::class, ['widget' => 'single_text',])
            ->add('endTime', DateTimeType::class, ['widget' => 'single_text',])
            ->add('backgroundColor', ColorType::class)
            ->add('borderColor', ColorType::class)
            ->add('textColor', ColorType::class)
            ->add('allDay', CheckboxType::class, ['attr' => ['class' => 'form-check-input']])
            ->add('client')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Visit::class,
        ]);
    }
}
