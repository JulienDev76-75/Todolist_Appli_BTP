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
            ->add('task_name')
            ->add('task_description')
            // ->add('task_creation')
            ->add('task_deadline')
            // ->add('task_statut')
            // ->add('project')
            ->add('enregistrer', SubmitType::class, [
                'attr' => ['class' => 'bg-dark text-white'],
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
