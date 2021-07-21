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
            ->add('project_name', null, [
                'label' => 'nom de votre projet :'
            ])
            ->add('projet_description', null, [
                'label' => 'description de votre projet :'
            ])
            // ->add('projet_creation')
            ->add('projet_deadline', null, [
                'label' => 'deadline de votre projet :'
            ])
            // ->add('user')
            ->add('enregistrer', SubmitType::class, [
                'attr' => ['class' => 'bg-danger text-white w-50 mt-3 mb-5'],
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
