<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('formerPassword', "text", array(
                    "required"=> false,
                    "mapped" => false
                ))
                ->add('password', "repeated", array(
                    'type' => "password",
                    'invalid_message' => 'Les mots de passent ne sont pas egaux',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => false,
                    'first_options'  => array('label' => 'Mot de passe'),
                    'second_options' => array('label' => 'Répété'),
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
