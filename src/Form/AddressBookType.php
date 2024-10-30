<?php

namespace App\Form;

use App\Entity\AddressBook;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder'=>'Prénom',
                    'class' => "form-control mb-3 w-lg-auto ",
                ]
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'placeholder' => 'Nom',
                    'class' => "form-control mb-3 w-lg-auto",
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Email',
                    'class' => "form-control mb-3 w-lg-auto",
                ]
            ])
            ->add('company',TextType::class, [
                'attr' => [
                    'placeholder' => 'Société',
                    'class' => "form-control mb-3 w-lg-auto",
                ]
            ])
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('note', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Informations compémentaires',
                    'class' => "form-control mb-3 ",
                ]
            ])
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AddressBook::class,
        ]);
    }
}
