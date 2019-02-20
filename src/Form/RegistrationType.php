<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RegistrationType extends ApplicationType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('firstName', TextType::class, $this->getConfiguration("Prénom", "votre prénom"))
                ->add('lastName', TextType::class, $this->getConfiguration("Nom", "Votre de nom"))
                ->add('email', EmailType::class, $this->getConfiguration("Email", "Votre Email"))
                ->add('picture', UrlType::class, $this->getConfiguration("Photo de profil", "Url de votre photo"))
                ->add('hash', PasswordType::class, $this->getConfiguration("Mot de passe", "votre mot de passe"))
                ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Confirmation de mot de passe", "votre  confirmation de mot de passe"))
                ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "votre introduction"))
                ->add('description', TextareaType::class, $this->getConfiguration("Description", "votre description"))

        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}
