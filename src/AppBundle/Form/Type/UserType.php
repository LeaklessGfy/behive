<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text')
                ->add('password', 'password')
                ->add('level', 'choice', array(
                    'choices' => array(
                        range(0, 50)
                    )
                ))
                ->add('email', 'email')
                ->add('avatar', 'file', array(
                    'data_class' => null
                ))
                ->add('games', 'entity', array(
                    'class' => 'AppBundle:Game',
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true
                ))
                ->add('roles', 'entity', array(
                    'class' => 'AppBundle:RolesCustom',
                    'choice_label' => 'name',
                    'multiple' => false
                ))
                ->add('badges', 'entity', array(
                    'class' => 'AppBundle:Badge',
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
        ));
    }
}
