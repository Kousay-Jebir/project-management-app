<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;

class NewTaskFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('taskName', null, ['label' => 'Task label', 'required' => true])
            ->add('taskDescription', null, ['label' => 'Description', 'required' => true])
            ->add('assignedUser', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'userName',
                'label' => 'Assignee',
                'choices' => $options['teamMembers']->getUsers()
            ])
            ->add('Add', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'teamMembers' => null
        ]);
    }
}
