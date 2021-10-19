<?php

namespace App\Form;

use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DataTransformer\EmailToClientTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ClientSelectTextType extends AbstractType
{
    public function __construct(private ClientRepository $clientRepo, private EntityManagerInterface $em)
    {}
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new EmailToClientTransformer($this->clientRepo, $this->em));
    }

    public function getParent()
    {
        return TextType::class;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'invalid_message' => 'Client not found!',
        ]);
    }
}
