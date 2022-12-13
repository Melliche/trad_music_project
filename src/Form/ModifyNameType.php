<?php

namespace App\Form;

use App\Entity\Instrument;
use App\Entity\Musician;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;

class ModifyNameType extends AbstractType
{
    #[Route( name: 'modify_profile')]

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


            ->add('firstName',FileType::class,[
                'label' => 'Prénom',
                'required' => true,

            ])
            ->add('lastName',FileType::class,[
                'label' => 'Nom',
                'required' => true,

            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'data_class' => null,
                'required' => false,
                'attr' => ['accept' => 'image/*']
            ])


            ->add('instruments', EntityType::class, [
                'class' => Instrument::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Musician::class,


        ]);
    }
}
