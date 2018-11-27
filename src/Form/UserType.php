<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'label.pseudo',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => 50
                    ])
                ],
                'attr' => [
                    'autofocus' => 'autofocus'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'label.email',
                'constraints' => [
                    new NotBlank(),
                    new Email()
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'label.password',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 6
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'btn.submit'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
