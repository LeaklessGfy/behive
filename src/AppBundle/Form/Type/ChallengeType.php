<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChallengeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text')
                ->add('cover', 'file', array(
                    'data_class' => null
                ))
                ->add('description', 'textarea')
                ->add('game', 'entity', array(
                    'class' => 'AppBundle:Game',
                    'choice_label' => 'name',
                    'multiple' => false
                ))
                ->add('limits', 'collection', array(
                    'entry_type'   => new ChallengeLimitType(),
                    'allow_add'    => true,
                    'allow_delete' => true
                ))
                ->add('isDaily', 'choice', array(
                    'label' => 'Challenge du jour',
                    'choices' => array(
                        true => "Oui",
                        false => "Non"
                    )
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Challenge',
        ));
    }
}
