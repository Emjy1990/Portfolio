<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Command;
use App\Entity\Adress;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\CategoryRepository;
use App\Repository\AdressRepository;
use App\Repository\DeliveryModeRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class CommandTypeNew extends AbstractType {

public function buildForm(FormBuilderInterface $builder, array $options){

    $builder
    ->add('deliveryAdress', EntityType::class, [
                'class'        => 'App:Adress',
                'query_builder' => function (AdressRepository $er) use ($options) {

                    return $er->createQueryBuilder('entity')
                                      ->where('entity.user = ' . $options['id']);

},
'choice_label' => 'street',
'label' => 'Adresse de livraison',
'help' => 'Veuillez verifier votre adresse de livraison',

])
    ->add('paymentAdress', EntityType::class, [
                'class'        => 'App:Adress',
                'query_builder' => function (AdressRepository $er) use ($options) {

                    return $er->createQueryBuilder('entity')
                                      ->where('entity.user = ' . $options['id']);

},

'choice_label' => 'street',
'label' => 'Adresse de facturation',
'help' => 'Veuillez verifier votre adresse de facturation',

            ])
            ->add('deliveryMode', EntityType::class, [
                        'class'        => 'App:DeliveryMode',

        'choice_label' => 'name',
        'label' => 'Mode livraison',
        'help' => 'Veuillez choisir un mode livraison',

                    ])
    ->add('paymentMode', EntityType::class, [
                'class'        => 'App:PaymentMode',

'choice_label' => 'name',
'label' => 'Mode de paiement',
'help' => 'Veuillez choisir un mode paiement',
])

    ;

}

public function configureOptions(OptionsResolver $resolver)
{
    $resolver->setDefaults([
        'data_class' => Command::class,
    ]);
    $resolver->setRequired(['id']);

}
}
