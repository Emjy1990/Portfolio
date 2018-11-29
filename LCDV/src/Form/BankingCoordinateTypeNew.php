<?php

namespace App\Form;

use App\Entity\BankingCoordinate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BankingCoordinateTypeNew extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la banque',
                'attr' => array('placeholder' => '')
            ])
            ->add('account', TextType::class, [
                'label' => 'Iban',
                'attr' => array('placeholder' => '')
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BankingCoordinate::class,
        ]);
    }
}
