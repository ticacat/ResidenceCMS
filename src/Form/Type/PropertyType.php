<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Valery Maslov
 * Date: 16.08.2018
 * Time: 10:39.
 */

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\DealType;
use App\Entity\District;
use App\Entity\Feature;
use App\Entity\Metro;
use App\Entity\Neighborhood;
use App\Entity\Property;
use App\Entity\User;
use App\Form\EventSubscriber\AddAgentFieldSubscriber;
use App\Form\EventSubscriber\AddDistrictFieldSubscriber;
use App\Form\EventSubscriber\AddMetroFieldSubscriber;
use App\Form\EventSubscriber\AddNeighborhoodFieldSubscriber;
use App\Form\EventSubscriber\UpdateDistrictFieldSubscriber;
use App\Form\EventSubscriber\UpdateMetroFieldSubscriber;
use App\Form\EventSubscriber\UpdateNeighborhoodFieldSubscriber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

final class PropertyType extends AbstractType
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.select_city',
                'label' => 'label.city',
            ])

            ->add('neighborhood', EntityType::class, [
                'class' => Neighborhood::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.select_neighborhood',
                'label' => 'label.neighborhood',
                'required' => false,
                'choices' => [],
            ])


            ->add('dealType', EntityType::class, [
                'class' => DealType::class,
                'choice_label' => 'name',
                'label' => 'label.deal_type',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'label.category',
            ])
            ->add('area', null, [
                'label' => 'label.area',
            ])->add('parcela', null, [
                'label' => 'label.parcela',
            ])
            ->add('bathrooms_number', null, [
                'label' => 'label.bathrooms_number',
            ])
            ->add('bedrooms_number', null, [
                'label' => 'label.bedrooms_number',
            ])
            ->add('energy_certificate', ChoiceType::class, [
                'label' => 'label.energy_certificate',
                'required' => false,
                'choices' => [
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C',
                    'D' => 'D',
                    'E' => 'E',
                    'F' => 'F',
                    'G' => 'G'
                ],
            ])
            ->add('energy_emission', null, [
                'label' => 'label.energy_emission',
            ])

            ->add('energy_consumption', null, [
                'label' => 'label.energy_consumption',
            ])



            ->add('address', null, [
                'label' => 'label.address',
            ])
            ->add('latitude', null, [
                'label' => 'label.latitude',
            ])
            ->add('longitude', null, [
                'label' => 'label.longitude',
            ])

            ->add('show_map', CheckboxType::class, [
                'label' => 'label.show_map',
                'label_attr' => ['class' => 'switch-custom'],
                'required' => false,
            ])
            ->add('price', null, [
                'label' => 'label.price',
            ])
            ->add('price_type', null, [
                'label' => 'label.price_type',
            ])


            ->add('show_slider_homepage', CheckboxType::class, [
                'label' => 'label.show_slider_homepage',
                'label_attr' => ['class' => 'switch-custom'],
                'required' => false,
            ])


            ->add('level', ChoiceType::class, [
                'label' => 'label.property_level',
                'choices' => [
                    'option.base' => 0,
                    'option.bronze' => 10,
                    'option.silver' => 20,
                    'option.gold' => 30,
                    'option.top' => 99
                ],
            ])

            ->add('available_now', CheckboxType::class, [
                'label' => 'label.available_now',
                'label_attr' => ['class' => 'switch-custom'],
                'required' => false,
            ])
            ->add('features', EntityType::class, [
                'class' => Feature::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                'label' => 'label.features',
            ])
            ->add('property_description', PropertyDescriptionType::class)
            ->add('property_status', ChoiceType::class, [
                'label' => 'label.property_status',
                'required' => false,
                'choices' => [
                    'reservado' => 'reservado',
                    'vendido' => 'vendido'
                ],
            ])
        ;



        $builder->addEventSubscriber(new AddNeighborhoodFieldSubscriber())
            ->get('city')->addEventSubscriber(new UpdateNeighborhoodFieldSubscriber());

     /*   $builder->addEventSubscriber(new AddDistrictFieldSubscriber())
            ->get('city')->addEventSubscriber(new UpdateDistrictFieldSubscriber());

        $builder->addEventSubscriber(new AddMetroFieldSubscriber())
            ->get('city')->addEventSubscriber(new UpdateMetroFieldSubscriber());

                */
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->addFieldsForAdmin($builder);
        }
    }

    private function addFieldsForAdmin(FormBuilderInterface $builder): FormBuilderInterface
    {
        $builder
            ->add('priority_number', null, [
                'label' => 'label.priority_number',
                'required' => false,
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'profile.full_name',
                'label' => 'label.agent',
            ])
            ->add('state', ChoiceType::class, [
                'label' => 'label.moderation_status',
                'choices' => [
                    'option.published' => 'published',
                    'option.private' => 'private',
                    'option.pending' => 'pending',
                    'option.rejected' => 'rejected',
                ],
            ]);

        $builder->addEventSubscriber(new AddAgentFieldSubscriber($this->security));

        return $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
