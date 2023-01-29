<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Title', null, [
                'label' => 'Titre de l\'article',
            ])
            ->add('Content', null, [
                'label' => 'Contenu de l\'article',
            ])
            ->add('Image', null, [
                'label' => 'URL de l\'image d\'illustration',
            ])
            ->add('Submit', SubmitType::class, [
                'label' => 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
