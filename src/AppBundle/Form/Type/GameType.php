<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text')
                ->add('cover', 'file', array(
                    'data_class' => null
                ))
                ->add('date', 'date')
                ->add('description', 'textarea')
                ->add('rating', 'choice', array(
                    'choices' => array(
                        range(0,5)
                    )
                ))
                ->add('buy', 'text')
                ->add('pegi', 'choice', array(
                    'choices' => array(
                        0 => 0,
                        3 => 3,
                        7 => 7,
                        12 => 12,
                        16 => 16,
                        18 => 18)
                ))
                ->add('categories', 'entity', array(
                    'class' => 'AppBundle:Category',
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Game',
        ));
    }
}
