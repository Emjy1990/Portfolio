<?php

namespace App\Form;

use App\Entity\Adress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdressTypeNew extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', IntegerType::class, [
                'label' => 'N° de rue',
                'attr' => array('placeholder' => '')
            ])
            ->add('street',  TextType::class, [
                'label' => 'Rue',
                'attr' => array('placeholder' => '')
            ])
            ->add('building',  TextType::class, [
                'label' => 'Batiment',
                'attr' => array('placeholder' => 'facultatif',
                'required' => false)
            ])
            ->add('etage', IntegerType::class, [
                'label' => 'N° d\'etage',
                'attr' => array('placeholder' => 'facultatif',
                'required' => false)
            ])
            ->add('cp', IntegerType::class, [
                'label' => 'Code postal',
                'attr' => array('placeholder' => '')
            ])
            ->add('city',  TextType::class, [
                'label' => 'Ville',
                'attr' => array('placeholder' => '')
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
            'required' => false
        ]);
    }
}
