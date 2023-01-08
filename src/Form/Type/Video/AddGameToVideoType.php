<?php

namespace App\Form\Type\Video;

use App\Entity\Video;
use App\Form\Model\Video\AddGameToVideoModel;
use App\Repository\VideoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddGameToVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('video', EntityType::class, [
            'class' => Video::class,
            'choices' => $options['videos'],
            'choice_label' => 'name'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AddGameToVideoModel::class,
            'videos' => [],
        ]);
    }
}
