<?php

namespace App\Form;

use App\Entity\Vote;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class VoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [ 'label' => 'Podaj swój email:', 'required' => true ])
            ->add('kohana', IntegerType::class, [ 'label' => 'Punkty dla Kohana:', 'required' => true, 'attr' => array('min' => 1, 'max' => 10) ])
            ->add('symfony', IntegerType::class, [ 'label' => 'Punkty dla Symfony:', 'required' => true, 'attr' => array('min' => 1, 'max' => 10)  ])
            ->add('laravel', IntegerType::class, [ 'label' => 'Punkty dla Laravel:', 'required' => true, 'attr' => array('min' => 1, 'max' => 10)  ])
            ->add('check_req', CheckboxType::class, [ 'label' => 'Musisz zaznaczyć:', 'required' => true ])
            ->add('check_req_not', CheckboxType::class, [ 'label' => 'A tego nie musisz:','required' => false ])
            ->add('save', SubmitType::class, [ 'label' => 'Wyślij swój głos',  'attr' => ['class' => 'btn btn-md btn-primary'] ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => ['id' => 'vote-form'],
            'data_class' => Vote::class,
        ]);
    }
}
