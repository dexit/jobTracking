<?php

namespace App\Form;

use App\Entity\JobApiServices;
use App\Entity\JobSearchSettings;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class JobSearchSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('what', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Intitulé de poste ex : Ouvrier polyvalent, Développeur, ...'
                ]
            ])
            ->add('city_autocomplete', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Rechercher une ville'
                ],
                'mapped' => false
            ])
            ->add('city', HiddenType::class, [
                'mapped' => false
            ])
            ->add('distance', NumberType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'aria-describedby' => 'distance',
                    'placeholder' => 'Distance'
                ]
            ])
            ->add('whatExclude', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Mots clés à exclure ex : alternance, stage, ...',
                ]
            ])
            ->add('maxDaysOld', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Visualiser les annonces ayant moins de ... ',
                    'min' => 1,
                    'max' => 8,
                ],
                'constraints' => [
                    new Assert\Range([
                        'min' => 1,
                        'max' => 8,
                        'notInRangeMessage' => 'Le nombre doit être compris entre {{ min }} et {{ max }}',
                    ]),
                ],
                'html5' => true,
                'empty_data' => 8
            ])
            ->add('jobApiServices', EntityType::class, [
                'class' => JobApiServices::class,
                'choice_label' => function (JobApiServices $service) {
                    return $service->getName() . ' -  <small class="form-text text-muted">' . $service->getDescription() .'</small>';
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
                'label' => 'Services de recherche d\'emploi',
                'attr' => ['class' => "form-control"], // Utilisé pour les "select", mais pas nécessaire ici avec les radio
                'expanded' => true, // Options sous forme de boutons radio
                'multiple' => true, // Un seul bouton peut être sélectionné
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JobSearchSettings::class,
        ]);
    }
}
