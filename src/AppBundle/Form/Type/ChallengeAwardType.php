<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChallengeAwardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text')
                ->add('points','number')
                ->add('badge', 'entity', array(
                    'class' => 'AppBundle:Badge',
                    'choice_label' => 'name',
                    'multiple' => false,
                    'required' => false
                ))
                ->add('game', 'entity', array(
                    'class' => 'AppBundle:Game',
                    'choice_label' => 'name',
                    'multiple' => false,
                    'required' => false
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ChallengeAward',
        ));
    }
}
