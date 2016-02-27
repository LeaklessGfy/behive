<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('formerPassword', "text", array(
                    "data_class" => null
                ))
                ->add('password', "repeated", array(
                    'type' => "password",
                    'invalid_message' => 'Les mots de passent ne sont pas egaux',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => false,
                    'first_options'  => array('label' => 'Mot de passe'),
                    'second_options' => array('label' => 'Répété'),
                ))
                ->add('avatar', 'file', array(
                    'data_class' => null,
                    'required' => false
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
