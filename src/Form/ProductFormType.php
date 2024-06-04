<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Products;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label mt-4'],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'textarea',
                    'rows' => 10,
                    'cols' => 100,
                    'maxlength' => 500,
                    'placeholder' => 'Enter your description here',
                    'required' => true,
                ],
            ])
            ->add('imagePath', FileType::class, [
                'attr' => ['class' => 'form-control mb-3'],
                'label_attr' => ['class' => 'form-label'],
                'required' => false,
                'mapped' => false
            ])
            ->add('price', MoneyType::class, [

                'label' => 'Price',
                'currency' => 'USD',
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('category',EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a category',
                'attr' => ['class' => 'form-control mb-3'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => $options['label'], // Use the dynamic label here
                'attr' => ['class' => 'form-control mb-3 btn btn-success'],
            ])
        ;


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
