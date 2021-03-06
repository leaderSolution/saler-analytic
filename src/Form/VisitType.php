<?php

namespace App\Form;

use App\Entity\Visit;
use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class VisitType extends AbstractType
{
    
    public function __construct(private ClientRepository $clientRepo)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('types', ChoiceType::class, [
                'placeholder' => 'Choose an option',
                'multiple' => true,
                'choices'  => [
                    'Assistance' => 1,
                    'Delivery' => 2,
                    'Recovery' => 3,
                    'Administrative' => 4,
                    'Commercial' => 5,
                    'Other' => 6,

                ],
            ])
            ->add('comment')
            ->add('startTime', DateTimeType::class, ['widget' => 'single_text',])
            ->add('endTime', DateTimeType::class, ['widget' => 'single_text',])
            ->add('backgroundColor', ColorType::class)

            ->add('allDay', CheckboxType::class, ['attr' => ['class' => 'form-check-input']])
            ->add('client', ClientSelectTextType::class)
            //->add('address', AddressType::class, [])
            ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Visit::class,
        ]);
    }
}
