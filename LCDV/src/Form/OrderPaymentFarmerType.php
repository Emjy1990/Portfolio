<?php
namespace App\Form;
use App\Entity\OrderPaymentFarmer;
use App\Entity\BankingCoordinate;
use App\Entity\User;
use App\Entity\StatusOrderPaymentFarmer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Repository\BankingCoordinateRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\LessThan;


class OrderPaymentFarmerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('amount', IntegerType::class, [
        'label' => 'Montant',
        'help' => 'Veuillez entrer un montant supérieur ou égal à 50 euros',
        'attr' => array('placeholder' => 'maximun ' . $options['money'] .' €' ),
        'constraints' => [

            new LessThan(['value' => $options['money'],
        'message' => 'Votre demande de virement ne peut être supérieur à la valeur de votre solde actuel qui est de ' . $options['money'] . ' €']),


        ],
        ])
        ->add('bankingCoordinate', EntityType::class, [
                    'class'        => 'App:BankingCoordinate',
                    'query_builder' => function (BankingCoordinateRepository $er) {
        return $er->createQueryBuilder('u')
        ->where('u.id  IN (:ids)')
        ->setParameter('ids', [1,4,5]);

    },
    'choice_label' => 'name',
    'label' => 'Coordonées bancaires',
    'help' => 'Veuillez choisir votre banque',

                ]);


    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderPaymentFarmer::class,
        ]);
        $resolver->setRequired(['money']);
    }
}
