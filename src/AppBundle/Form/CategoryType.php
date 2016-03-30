<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType {

    public $em;
    public $kat;
    public $choices;
    
    function __construct(){

    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('title', NULL, array(
                    'label' => 'Nazwa',
                ))
               
                ->add('parent', 'entity', array(
                    'label' => 'Wybierz rodzica',
                    'class' => 'AppBundle:Category',
                    'required' => true,
                    'property' => 'title',
                    'empty_value' => '',
                    'query_builder' => function($er) {
                        return $er->createQueryBuilder('p')
                                ->orderBy('p.lft', 'ASC');
                    },
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Category'
        ));
    }

    public function getName() {
        return 'appbundle_categorytype';
    }

}
