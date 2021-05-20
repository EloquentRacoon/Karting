<?php

namespace App\Form;

use App\Entity\Soortactiviteit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SoortactiviteitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam' ,TextType::class)
            ->add('beschrijving', TextareaType::class)
            ->add('tijdsduur', TimeType::class, [
                'attr' => ['class' => 'js-timepicker', 'placeholder' => 'hh:mm'],
                'widget' => 'single_text', 'html5' => false,])
            ->add('prijs' , NumberType::class, [
                'scale' => 2,
                ])
            ->add('minLeeftijd', IntegerType::class, [

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Soortactiviteit::class,
        ]);
    }
}
