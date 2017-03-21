<?php

namespace JOBEET\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use JOBEET\PlatformBundle\Repository\CategoryRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AdvertType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {

    $pattern = 'D%';
    $builder
    ->add('date',      DateTimeType::class)
    ->add('title',     TextType::class)
    ->add('author',    TextType::class)
    ->add('content',   TextareaType::class)
    ->add('published', CheckboxType::class, array('required' => false))
    ->add('image',     ImageType::class)
       /*
       * Rappel :
       ** - 1er argument : nom du champ, ici « categories », car c'est le nom de l'attribut
       ** - 2e argument : type du champ, ici « CollectionType » qui est une liste de quelque chose
       ** - 3e argument : tableau d'options du champ
       */
       ->add('categories', CollectionType::class, array(
        'entry_type'   => CategoryType::class,
        'allow_add'    => true,
        'allow_delete' => true
        ))
       ->add('categories', EntityType::class, array(
        'class'         => 'JOBEETPlatformBundle:Category',
        'choice_label'  => 'name',
        'multiple'      => true,
        'query_builder' => function(CategoryRepository $repository) use($pattern) {
          return $repository->getLikeQueryBuilder($pattern);
        }
        ))
       ->add('save',      SubmitType::class);
     }

     public function configureOptions(OptionsResolver $resolver)
     {
      $resolver->setDefaults(array(
        'data_class' => 'JOBEET\PlatformBundle\Entity\Advert'
        ));
    }
  }
