<?php

namespace App\Form;

use App\Entity\Transaction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_transaction')
            ->add('type')
            ->add('price_total')
            ->add('status')
            ->add('status_payment')
            ->add('note')
            // ->add('id_contact')
            // ->add('id_medicament')
        ;

        // ->add('contact',EntityType::class,['class'=>Contact::class,
        // // 'attr' => [
        // //     'class' => 'col-md-6 form-control',
        // // ],
        // 'choice_label'=>'name',
        // 'label'=>'name'
        // ])
    

    
        // ->add('id_medicament_id',EntityType::class,['class'=>Medicament::class,
        // 'label'=>'name',
        // 'choice_label'=>'name',
        
        // // 'attr' => [
        // //     'class' => 'form-check-input',
        // // ],
        // 'expanded'  => true,
        // // 'mapped'  => true,
        // 'multiple' => true
        // ])
        // ->add('medicament_id', CollectionType::class, [
        //     // each entry in the array will be an "email" field
        //     'entry_type' => MedicamentType::class,
        //     // these options are passed to each "email" type
        //     'entry_options' => [
        //         'attr' => ['class' => 'the_new_product'],
        //     ],
        // ]);
      
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
