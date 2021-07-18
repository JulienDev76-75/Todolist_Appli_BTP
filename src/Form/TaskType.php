<?php

namespace App\Form;

use App\Entity\Tasks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('task_name', null, [
                'label' => 'nom de votre tâche :'
            ])
            ->add('task_description', null, [
                'label' => 'mettez ici la liste de vos tâches :) :'
            ])
            // ->add('task_creation')
            ->add('task_deadline', null, [
                'label' => 'deadline de votre tâche :'
            ])
            // ->add('task_statut')
            // ->add('project')
            ->add('enregistrer', SubmitType::class, [
                'attr' => ['class' => 'bg-danger text-white'],
                'row_attr' => ['class' => 'text-center']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tasks::class,
        ]);
    }
}
