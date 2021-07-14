<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project_name')
            ->add('projet_description')
            // ->add('projet_creation')
            ->add('projet_deadline')
            // ->add('user')
            ->add('enregistrer', SubmitType::class, [
                'attr' => ['class' => 'bg-dark text-white'],
                'row_attr' => ['class' => 'text-center']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
