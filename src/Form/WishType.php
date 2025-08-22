<?php

namespace App\Form;

use App\Entity\Wish;
use Faker\Provider\Text;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'error_bubbling' => true])
            ->add('description', TextareaType::class, [
                'error_bubbling' => true] )
            ->add('author', TextType::class, [
                'error_bubbling' => true] )
            ->add('wishImage', FileType::class, [
                'mapped' => false,
                'label' => "Image",
                'constraints' => [
                    new Image(
                        maxSize: '1M',
                        maxSizeMessage: 'Maximum file size is 2 MB',
                        extensions: [
                            'image/jpeg',
                            'image/png'
                        ],
                        extensionsMessage: 'Only jpeg, png, jpg files are allowed'
                    )
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}
