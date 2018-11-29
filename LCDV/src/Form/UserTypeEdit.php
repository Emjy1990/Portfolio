<?php
namespace App\Form;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class UserTypeEdit extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username', TextType::class, [
            'label' => 'Nom d\'utilisateur',
            'attr' => array('placeholder' => '')
        ])
        ->add('email', EmailType::class, [
            'label' => 'Email',
            'attr' => array('placeholder' => '')
        ])
        ->add('firstname', TextType::class, [
            'label' => 'Prénom',
            'attr' => array('placeholder' => '')
        ])
        ->add('lastname', TextType::class, [
            'label' => 'Nom',
            'attr' => array('placeholder' => '')
        ])
        ->add('password', RepeatedType::class, array(
            'type' => PasswordType::class,
            'first_options'  => array('label' => 'Mot de passe'),
            'second_options' => array('label' => 'Répétez votre de mot passe'),
        ))
        ->add('image', FileType::class, array('label' => 'Photo de profil (format jpeg)',
        'data_class' => null))
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
