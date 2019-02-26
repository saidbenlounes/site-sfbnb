<?php

namespace App\Form;

use App\Entity\Comment;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CommentType extends ApplicationType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('rating', IntegerType::class, $this->getConfiguration("Note", "donner une note", ['attr' => ['min' => 0, 'max' => 5, 'step' => 1]]))
                ->add('content', TextareaType::class, $this->getConfiguration("commentaire", "votre commentaire"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }

}
