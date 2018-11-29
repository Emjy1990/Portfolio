<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;

class ProductTypeNew extends AbstractType {

public function buildForm(FormBuilderInterface $builder, array $options){

    $builder
    ->add('name', TextType::class, [
        'label' => 'Nom du produit',
        'attr' => array('placeholder' => '')
    ])
    ->add('description', TextType::class, [
        'label' => 'Description du produit',
        'attr' => array('placeholder' => 'votre description ne doit pas être trop longue')
    ])
    ->add('price', IntegerType::class, [
        'label' => 'Prix du produit',
        'attr' => array('placeholder' => 'Le prix est affiché en kilo')
    ])
    ->add('quantity', IntegerType::class, [
        'label' => 'Quantité disponible',
        'attr' => array('placeholder' => 'veuillez entrer la quantité disponible pour votre produit')
    ])
    ->add('category', EntityType::class, [
                'class'        => 'App:Category',
                'query_builder' => function (CategoryRepository $er) {
    return $er->createQueryBuilder('u')
    ->where('u.id  IN (:ids)')
    ->setParameter('ids', [1,2,3,4,5]); },

        'choice_label' => 'name',
        'label' => 'Catégorie produit',
        'help' => 'Veuillez choisir votre une catégorie pour votre produit',
    ])
    ->add('image', FileType::class, array('label' => 'Photo du produit (format jpeg)',
    'required' => true,
    'data_class' => null
                        ))
    ;
}
public function configureOptions(OptionsResolver $resolver)
{
    $resolver->setDefaults([
        'data_class' => Product::class,
    ]);

}
}
