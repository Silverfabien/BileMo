<?php

namespace BileMoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fullname', TextType::class, ['label' => 'Pseudo:', 'required' => true])
                ->add('email', EmailType::class, ['label' => 'Votre email', 'required' => true])
                ->add('country', TextType::class, ['label' => 'Le Pays:', 'required' => true])
                ->add('city', TextType::class, ['label' => 'Votre Ville', 'required' => true])
                ->add('address', TextType::class, ['label' => 'Votre adresse', 'required' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BileMoBundle\Entity\Client'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bilemobundle_clientbilemo';
    }
}
