<?php

namespace App\Form\Type\Game;

use App\Business\Model\IgdbRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchGameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('platforms', ChoiceType::class, [
                'mapped' => false,
                'choices' => [
                    'Nintendo 64' => 4,
                    'NES' => 18,
                    'Super NES' => 19,
                    'Game Boy' => 33,
                    'Game Boy Color' => 22,
                    'Game Boy Advance' => 24,
                    'Nintendo DS' => 20,
                    'Nintendo 3DS' => 37,
                    'GameCube' => 21,
                    'Wii' => 5,
                    'Wii U' => 41,
                    'Switch' => 130,

                    'Playstation 1' => 7,
                    'Playstation 2' => 8,
                    'Playstation 3' => 9,
                    'Playstation Vita' => 46,
                    'Playstation 4' => 48,
                    'Playstation 5' => 167,

                    'PC (Microsoft Windows)' => 3,
                    'Xbox' => 11,
                    'Xbox 360' => 12,
                    'Xbox One' => 49,
                    'Xbox Series X|S' => 49,
                ],
                'multiple' => true,
                'expanded' => false,
                'required' => false,
            ])
            ->add('fieldOrder', ChoiceType::class, [
                'choices' => [
                    'Score' => 'rating',
                    'Date de sortie' => 'first_release_date',
                ],
                'required' => false,
            ])
            ->add('sortOrder', ChoiceType::class, [
                'choices' => [
                    'Plus petit au plus grand' => 'asc',
                    'Plus grand au plus petit' => 'desc',
                ],
                'data' => 'asc',
            ])
            ->add('genres', ChoiceType::class, [
                'choices' => [
                    "Fighting" => 4,
                    "Shooter" => 5,
                    "Music" => 7,
                    "Platform" => 8,
                    "Puzzle" => 9,
                    "Racing" => 10,
                    "Real Time Strategy (RTS)" => 11,
                    "Role-playing (RPG)" => 12,
                    "Simulator" => 13,
                    "Sport" => 14,
                    "Strategy" => 15,
                    "Turn-based strategy (TBS)" => 16,
                    "Tactical" => 24,
                    "Quiz/Trivia" => 26,
                    "Hack and slash/Beat 'em up" => 25,
                    "Pinball" => 30,
                    "Adventure" => 31,
                    "Arcade" => 33,
                    "Visual Novel" => 34,
                    "Indie" => 32,
                    "Card & Board Game" => 35,
                    "MOBA" => 36,
                    "Point-and-click" => 2,
                ],
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'mapped' => false,
            ])
            ->add('search', TextType::class, [
                'required' => false,
            ])
            ->add('limit', IntegerType::class)
            ->add('page', IntegerType::class, ['mapped' => false, 'data' => 1])
            ->add('fieldsNotNullable', ChoiceType::class, [
                'choices' => [
                    'Score' => 'rating',
                    'Genres' => 'genres',
                    'Titre' => 'name',
                    'Images' => 'screenshots',
                ],
                'multiple' => true,
                'expanded' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => IgdbRequest::class,
            'method' => Request::METHOD_GET,
        ]);
    }
}
