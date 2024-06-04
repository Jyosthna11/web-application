<?php

namespace App\Form;

use App\Entity\UserDetails;
#use Doctrine\DBAL\ParameterType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Regex;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('First_name', TextType::class,[
                'label'=>'First_name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('Last_name', TextType::class,[
                'label'=>'Last_name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', EmailType::class,[
                'label'=>'email',
                'attr' => ['class' => 'form-control']
            ])
            ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
                'first_options' => ['label' => 'Password','attr' => ['class' => 'form-control']],
                'second_options' => ['label' => 'Repeat Password','attr' => ['class' => 'form-control']]
                ])
            ->add('phoneNumber', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\+?\d{1,15}$/',
                        'message' => 'Please enter a valid 10-digit phone number.',
                    ]),
                ]
                ])

            ->add('submit', SubmitType::class, [
                'attr'=> [
                    'class' => 'btn btn-success pull-right'
                ]
            ]);

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class'=> UserDetails::class
        ]);
    }
}
