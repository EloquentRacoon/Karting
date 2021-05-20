<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class,[
                'label' => 'Gebruikersnaam'
            ])
            ->add('email')
            ->add('voorletters')
            ->add('tussenvoegsel',null, [
                'mapped' => false,
            ])
            ->add('achternaam')
            ->add('adres')
            ->add('postcode')
            ->add('woonplaats')
            ->add('telefoon')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
