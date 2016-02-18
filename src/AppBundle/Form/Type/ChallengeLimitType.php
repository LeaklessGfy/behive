<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChallengeLimitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('begin', 'number')
                ->add('end', 'number')
                ->add('points', 'number')
                ->add('awards', 'entity', array(
                    'class' => 'AppBundle:ChallengeAward',
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ChallengeLimit',
        ));
    }
}
