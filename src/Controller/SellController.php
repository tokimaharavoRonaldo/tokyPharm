<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use tidy;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\PharamacienRepository;
use App\Repository\TransactionRepository;
use App\Repository\TransactionLineRepository;
use App\Repository\ContactRepository;
use App\Repository\MedicamentRepository;

use App\Entity\Transaction;
use App\Entity\Client;
use App\Entity\Medicament;
use App\Entity\Pharamacien;
use App\Entity\TransactionLine;
use App\Form\TransactionType;

class SellController extends AbstractController
{
    /**
     * @Route("/sell", name="app_sell")
     */
    public function index(TransactionRepository $sellRepository,MedicamentRepository $productRepository ,ContactRepository $contactRepository,Request $request): Response
    {

           $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // $hasAccess = $this->isGranted('ROLE_ADMIN');
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $term='';
        if ( $request->query->has('input_search')) {
            $term = $request->query->get('input_search');

            if (!empty($term)) {
            $all_sell=$sellRepository->findAll();
            $qb = $sellRepository->createQueryBuilder('sell');
          // $qb->select('cl.type');
          $qb->where("sell.type = 'sell'");
          $qb->join("sell.id_contact",'contact');
            $qb->join("sell.transactionLines",'tr_line');
            $qb->join("tr_line.id_medicament",'medicament');
             $qb->where('medicament.name LIKE :term');
          $qb ->setParameter('term','%'.$term.'%');
           $qb->orWhere('contact.name LIKE :term2');
          $qb ->setParameter('term2','%'.$term.'%');
        //   $qb->where('sell.id LIKE :term');
        //   $qb ->setParameter('term','%'.$term.'%');
        //   $qb->where('sell.id_contact.name LIKE :term');
        //   $qb ->setParameter('term','%'.$term.'%');
          $result=$qb->getQuery()->getResult();
          //  dd($result[4]->getIdContact()->getName());
          return $this->render('sell/index.html.twig', [
              'sells' => $result,
              'term' => $term
          ]);
        }
    }
        $all_sell=$sellRepository->findAll();
          $qb = $sellRepository->createQueryBuilder('sell');
        // $qb->select('cl.type');
        $qb->where("sell.type = 'sell'");
        $qb->orderBy('sell.id','DESC');
        $result=$qb->getQuery()->getResult();
        //  dd($result[4]->getIdContact()->getName());
        return $this->render('sell/index.html.twig', [
            'sells' => $result,
            'term' => $term
        ]);
    }

     /**
     * @Route("/sell/create", name="app_sell_create")
     */
    public function create(Request $request,MedicamentRepository $productRepository ,ContactRepository $contactRepository): Response
    { 
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $transaction= new Transaction();
        $form = $this->createForm(TransactionType::class,$transaction);
        $product=$productRepository->findAll();
         $qb = $contactRepository->createQueryBuilder('contact');
        // $qb->select('cl.type');
        $qb->where("contact.type = 'client'");
        $contact=$qb->getQuery()->getResult();
        // $contact=$contactRepository->findAll();
        $date=new \DateTime();
        $newDate = $date->format('Y-m-d');

      $form->handleRequest($request);
        if ($form->isSubmitted() ) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($transaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_sell');
        }
        $status_payments=['paid','due'];
        $statuses=['final','draft'];
        $mode_payments=['cache','transaction1','transaction2'];
       
        return $this->render('sell/create.html.twig', [
            'form' => $form->createView(),
            'products' => $product,
            'date' => $newDate,
             'contacts' => $contact,
             'status_payments' => $status_payments,
             'statuses' => $statuses,
             'mode_payments' => $mode_payments
        ]); 
      
    }

      /**
     * @Route("/sell/store", name="app_sell_store")
     */
    public function store(Request $request,MedicamentRepository $productRepository ,ContactRepository $contactRepository,TransactionLineRepository $transactionLineRepository)
    { 

        $date_tr=$request->get('date_transaction');
 
        $the_date=date('Y-m-d H:i:s',strtotime($date_tr));
        $date_transaction=new \DateTime($the_date);

        $total_amount=$request->get('total_amount');
        // $paid_on=$request->get('paid_on');

        $type=$request->get('type');
        $client=$request->get('contact');
        $status_payment=$request->get('status_payment');
        $final_total=$request->get('final_total');
        $status=$request->get('status');
        // $status_payment=$request->get('status_payment');
        $note=$request->get('note');
        $products=$request->get('product');
      
        //payment
        $mode_payment=$request->get('mode_payment');
        $payment=$request->get('payment');


        if (empty($date_transaction || $total_amount || $client)){
            return $this->redirectToRoute('app_sell');
            }
        
        else{
        $entityManager=$this->getDoctrine()->getManager();
        $sell=new Transaction;
        $sell->setDateTransaction($date_transaction);
        $sell->setPriceTotal($final_total);
        $sell->setType($type);
        $sell->setNote($note);
        $sell->setStatus($status);

        $contact=$contactRepository->find($client);

        $sell->setIdContact($contact);
    
        $sell->setModePayment($mode_payment);
        $sell->setStatusPayment($status_payment);
        $sell->setTotalPaid($payment);
        
        
        $entityManager->persist($sell);
        
        
        $entityManager->flush();
        $sell->setNumFacture($this->number_format($sell->getId()));
        $entityManager->persist($sell);
        $entityManager->flush();
    
        //add product
        foreach($products as $product){
            $medicament=$productRepository->find($product['product_id']);

            $entityManager=$this->getDoctrine()->getManager();

            $transactionLine= new TransactionLine();
            $transactionLine->setIdTransaction($sell);
        $transactionLine->setIdMedicament($medicament);
        $transactionLine->setPrice($product['product_price_total']);
        $transactionLine->setQtyCarton($product['qty_carton']);
        $transactionLine->setQtyBoite($product['qty_boite']);
        $transactionLine->setQtyPlaquette($product['qty_plaquette']);

        $entityManager->persist($transactionLine);
        
        $entityManager->flush();

        }
        return $this->redirectToRoute('app_sell');
        }
    
    }

     /**
     * @Route("/sell/{id}", name="app_sell_show", methods={"GET"})
     */
    public function show(Transaction $sell,$id,TransactionRepository $transactionRepository ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $transaction=$transactionRepository->find($id);
    //    dd($transaction);
        return $this->render('sell/show.html.twig', [
            'sell' => $transaction,
        ]);
    }

     /**
     * @Route("/sell/{id}/edit", name="app_sell_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Transaction $sell,TransactionRepository $sellRepository,$id,ContactRepository $contactRepository,MedicamentRepository $productRepository): Response
    {
       $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

     $all_sell=$sellRepository->find($id);
     $product=$productRepository->findAll();
    //  $contact=$contactRepository->findAll();
    $qb = $contactRepository->createQueryBuilder('contact');
    // $qb->select('cl.type');
    $qb->where("contact.type = 'client'");
    $contact=$qb->getQuery()->getResult();

     //transaction line
     $transaction_lines=$all_sell->getTransactionLines();
     $count= count($transaction_lines);
     $status_payments=['paid','due'];
     $statuses=['final','draft'];
     $mode_payments=['cache','transaction1','transaction2'];
    //  dd($transaction_line);
     return $this->render('sell/edit.html.twig', [
        'sell' => $all_sell,
        'contacts' => $contact,
            'product' => $product,
            'mode_payments' => $mode_payments,
            'status_payments' => $status_payments,
            'statuses' => $statuses,
            'transaction_lines' => $transaction_lines,
            'count' => $count
        ]);

        
    }

       /**
     * @Route("/sell/update/id={id}", name="app_sell_udpate")
     */
    public function update(Request $request,$id,MedicamentRepository $productRepository ,TransactionRepository $sellRepository, TransactionLineRepository $sellLineRepository,ContactRepository $contactRepository)
    { 
        // dd($request);

        $date_tr=$request->get('date_transaction');
 
        $the_date=date('Y-m-d H:i:s',strtotime($date_tr));
        $date_transaction=new \DateTime($the_date);

        $total_amount=$request->get('total_amount');
        // $paid_on=$request->get('paid_on');

        $type=$request->get('type');
        $client=$request->get('contact');
        $status_payment=$request->get('status_payment');
        $final_total=$request->get('final_total');
        $status=$request->get('status');
        // $status_payment=$request->get('status_payment');
        $note=$request->get('note');
        $products=$request->get('product');
      
        //payment
        $mode_payment=$request->get('mode_payment');
        $payment=$request->get('payment');


        if (empty($date_transaction || $total_amount || $client)){
            return $this->redirectToRoute('app_sell');
            }
        
        else{
        $entityManager=$this->getDoctrine()->getManager();
        // $sell=new Transaction;
        $sell=$entityManager->getRepository(Transaction::class)->find($id);
        // $sell=$sellRepository->find($id);
        $sell->setDateTransaction($date_transaction);
        $sell->setPriceTotal($final_total);
        $sell->setType($type);
        $sell->setNote($note);
        $sell->setStatus($status);

        $contact=$contactRepository->find($client);

        $sell->setIdContact($contact);
    
        $sell->setModePayment($mode_payment);
        $sell->setStatusPayment($status_payment);
        $sell->setTotalPaid($payment);
     
        $entityManager->persist($sell);
        
        $entityManager->flush();

        $sell->setNumFacture($this->number_format($sell->getId()));
        $entityManager->persist($sell);
        $entityManager->flush();

        // removeTransactionLine
        foreach( $sell->getTransactionLines() as $tr_line){
        $tr_line_id=$tr_line->getId();
       
        $entityManager=$this->getDoctrine()->getManager();
        $sell_line=$entityManager->getRepository(TransactionLine::class)->find($tr_line_id);
        $entityManager->remove($sell_line);
        }
        
        //add product
        foreach($products as $product){
            $medicament=$productRepository->find($product['product_id']);

            $entityManager=$this->getDoctrine()->getManager();

            $transactionLine= new TransactionLine();
            $transactionLine->setIdTransaction($sell);
        $transactionLine->setIdMedicament($medicament);
        $transactionLine->setPrice($product['product_price_total']);
        $transactionLine->setQtyCarton($product['qty_carton']);
        $transactionLine->setQtyBoite($product['qty_boite']);
        $transactionLine->setQtyPlaquette($product['qty_plaquette']);

        $entityManager->persist($transactionLine);
        
        $entityManager->flush();
    
        }
    return $this->redirectToRoute('app_sell');
}    
 

    }

      /**
     * @Route("/sell/delete/{id}", name="app_sell_delete")
     */
    public function delete(Request $request, Transaction $sell,$id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

      //  if ($this->isCsrfTokenValid('delete'.$sell->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

        //remove sellLine
        foreach( $sell->getTransactionLines() as $tr_line){
        $tr_line_id=$tr_line->getId();
       
        $sell_line=$entityManager->getRepository(TransactionLine::class)->find($tr_line_id);
        $entityManager->remove($sell_line);
        }

            $entityManager->remove($sell);
            $entityManager->flush();

        return $this->redirectToRoute('app_sell');
    }

         /**
     * @Route("/list-medicaments", name="app_list_medicaments", methods={"GET"})
     */
    public function listMedicaments(Request $request,MedicamentRepository $productRepository)
    {
        
            if ( $request->query->has('term')) {
                $term = $request->query->get('term');
            

                if (!empty($term)) {
                    $medicament=$productRepository->findAll();
          $qb = $productRepository->createQueryBuilder('medicament');
        // $qb->select('cl.type');
        $qb->where('medicament.name LIKE :term');
        $qb ->setParameter('term','%'.$term.'%');
        $responses=$qb->getQuery()->getResult();
        $product=array();
        foreach ($responses as $response) {
            array_push($product, array(
                'medicament_id' => $response->getId(),
                'name' => $response->getName()
           
            ));
        }
 
        
                return new Response(json_encode($product));
                }
            }
            return $this->redirectToRoute('app_sell_create');
            
   
    }



       /**
     * @Route("/get-product-row", name="get_list_prodcut_row", methods={"GET"})
     */
    public function getTransactionDropshippingRow(Request $request,MedicamentRepository $productRepository)
    {
        $term = $request->query->get('term');
            $row_index = $request->query->get('row_index');
            $medicament_id = $request->query->get('medicament_id');
            $is_edit = $request->query->get('is_edit');
            $count = $request->query->get('count');

            $qb = $productRepository->createQueryBuilder('medicament');
            // $qb->select('cl.type');
            // $qb->where('medicament.name LIKE :term');
            // $qb ->setParameter('term','%'.$term.'%');
            $qb->where("medicament.id = '$medicament_id'");
            $response=$qb->getQuery()->getResult();

            $product = array(
                'medicament_id' => $response[0]->getId(),
                'name' => $response[0]->getName(),
                // 'sku' => $product->sku,
                'price_boite' => $response[0]->getPriceUnitBoite(),
                'price_carton' => $response[0]->getPriceUnitCarton(),
                'price_plaquette' => $response[0]->getPriceUnitPlaquette()
                
            );

            if($is_edit == 1){
                $index=$row_index + $count;
                return $this->render('sell/edit_medicament_table_row.html.twig', [
                    'row_index' => $index,
                    'i' => $row_index,
                    'product' => $product,
                ]);
            }

            return $this->render('sell/medicament_table_row.html.twig', [
            'row_index' => $row_index,
            'i' => $row_index,
            'product' => $product,
        ]);
     
            

    }

    public function number_format($number){
        $length=4;
        return str_pad($number,$length,"0", STR_PAD_LEFT);
    }
    
}
