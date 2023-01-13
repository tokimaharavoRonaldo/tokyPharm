<?php

namespace App\Form;

use App\Entity\Medicament;
use App\Entity\TheNewStock;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\StockType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class MedicamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('mode_conso')
            // ->add('date_peremp')
            ->add('cible')
            ->add('note')
            ->add('stock')
            ->add('effet_secondaire')
            ->add('date_peremp',DateType::class)
        
            // ->add('transaction_id')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medicament::class,
        ]);
    }

    
}
