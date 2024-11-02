<?php

namespace App\Form;

use App\Entity\JobApiServices;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobApiServicesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label'=>false, 
            'attr'=>[
                'class'=>'form-control my-1',
                'placeholder' => 'Nom du service Api'
            ]])
            ->add('functionName', TextType::class, ['label'=>false, 
            'attr'=>[
                'class'=>'form-control my-1',
                'placeholder' => 'Nom de la fonction dans ApiService'
            ]])
            ->add('url', TextType::class, ['label'=>false, 
            'attr'=>[
                'class'=>'form-control my-1',
                'placeholder' => 'url du site principal'
            ]])
     
            ->add('description', TextareaType::class, ['label'=>false, 
            'attr'=>[
                'class'=>'form-control my-1',
                'placeholder' => 'Description'
            ]])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JobApiServices::class,
        ]);
    }
}
