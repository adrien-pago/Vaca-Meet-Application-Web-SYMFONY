<?php

namespace App\Form;

use App\Entity\Camping;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('nomCamping', TextType::class)
            ->add('rgpdAccepted', CheckboxType::class, [
                'label' => 'J\'accepte les règlements RGPD',
                'required' => true, // ou false selon vos besoins
            ])
            ->add('register', SubmitType::class, ['label' => 'Créer un compte']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Camping::class,
        ]);
    }
}
