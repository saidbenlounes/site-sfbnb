<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;

class BookingType extends ApplicationType {

    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer) {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('startDate', TextType::class, $this->getConfiguration("Date d'arrivée", "Tvotre date d'arrivée"))
                ->add('endDate', TextType::class, $this->getConfiguration("Date de départ", "votre date de depart"))
                ->add('comment', TextareaType::class, $this->getConfiguration("Commentaire", "votre date de commentaire", ["required" => false]))

        ;
        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            "validation_groupe" => ["Default", "Front"]
        ]);
    }

}
