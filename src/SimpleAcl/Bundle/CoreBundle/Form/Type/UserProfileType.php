<?php

namespace SimpleAcl\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserProfileType extends AbstractType
{
    private $class;

    /**
     * @param string $class The UserProfile class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, array(
                'label' => 'simple_acl.form.first_name'
            ))
            ->add('lastName', null, array(
                'label' => 'simple_acl.form.last_name'
            ))
            ->add('birthday', null, array(
                'label' => 'simple_acl.form.birthday'
            ))
            ->add('phoneNumber', null, array(
                'label' => 'simple_acl.form.phone_number'
            ))
            ->add('nickname', null, array(
                'label' => 'simple_acl.form.nickname'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'            => $this->class,
            'cascade_validation'    => true,
            'csrf_protection'       => false,
        ));
    }

    public function getName()
    {
        return 'simple_acl_user_profile';
    }
}
 