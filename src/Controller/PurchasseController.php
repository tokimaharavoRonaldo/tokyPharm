<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use tidy;
use Symfony\Component\HttpFoundation\Request;
// use App\Repository\TransactionRepository;
use App\Repository\ClientRepository;

use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;


use App\Repository\PharamacienRepository;
use App\Repository\TransactionRepository;
use App\Repository\ContactRepository;
use App\Repository\MedicamentRepository;



use App\Entity\Transaction;
// use App\Entity\Transaction;
use App\Entity\Client;
use App\Entity\TransactionLine;
use App\Entity\Medicament;
use App\Entity\Pharamacien;
// use App\Form\TransactionType;
use App\Form\TransactionType;
new \Omines\DataTablesBundle\DataTablesBundle();

class PurchasseController extends AbstractController
{

    /**
     * @Route("/purchasse", name="app_purchasse")
     */
    public function index(TransactionRepository $purchasseRepository,MedicamentRepository $productRepository ,ContactRepository $contactRepository,Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $term='';
        $type='purchasse';
        if ( $request->query->has('input_search')) {

            $term = $request->query->get('input_search');

            if (!empty($term)) {
                $result=$purchasseRepository->search_transaction($term,$type);
          
            //  dd($result[4]->getIdContact()->getName());
            return $this->render('purchasse/index.html.twig', [
                'purchasses' => $result,
                'term' => $term
            ]);
          
         
        }
        }
        
        $all_purchasse=$purchasseRepository->findAll();
          $qb = $purchasseRepository->createQueryBuilder('purchasse');
        $qb->where("purchasse.type = 'purchasse'");
        $qb->orderBy('purchasse.id','DESC');
        $result=$qb->getQuery()->getResult();
        return $this->render('purchasse/index.html.twig', [
            'purchasses' => $result,
            'term' => $term,
        ]);
    }

     /**
     * @Route("/purchasse/create", name="app_purchasse_create")
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
        $qb->where("contact.type = 'fournisseur'");
        $contact=$qb->getQuery()->getResult();
        // $contact=$contactRepository->findAll();
        $date=new \DateTime();
        $newDate = $date->format('Y-m-d');

      $form->handleRequest($request);
        if ($form->isSubmitted() ) {
            // dd($the_new_transaction);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($transaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_purchasse');
        }
        $status_payments=['paid','due'];
        $statuses=['final','draft'];
        $mode_payments=['cache','transaction1','transaction2'];
       
        return $this->render('purchasse/create.html.twig', [
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
     * @Route("/purchasse/store", name="app_purchasse_store")
     */
    public function store(Request $request,MedicamentRepository $productRepository ,ContactRepository $contactRepository)
    { 

        // dd($request);
        $date_tr=$request->get('date_transaction');
 
        $the_date=date('Y-m-d H:i:s',strtotime($date_tr));
        $date_transaction=new \DateTime($the_date);

        $total_amount=$request->get('total_amount');
        // $paid_on=$request->get('paid_on');

        $type=$request->get('type');
        $fournisseur=$request->get('contact');
        $status_payment=$request->get('status_payment');
        $final_total=$request->get('final_total');
        $status=$request->get('status');
        // $status_payment=$request->get('status_payment');
        $note=$request->get('note');
        $products=$request->get('product');
      
        //payment
        $mode_payment=$request->get('mode_payment');
        $payment=$request->get('payment');


        if (empty($date_transaction || $total_amount || $fournisseur)){
            return $this->redirectToRoute('app_purchasse');
            }
        
        else{
        $entityManager=$this->getDoctrine()->getManager();
        $purchasse=new Transaction;
        $purchasse->setDateTransaction($date_transaction);
        $purchasse->setPriceTotal($final_total);
        $purchasse->setType($type);
        $purchasse->setNote($note);
        $purchasse->setStatus($status);

        $contact=$contactRepository->find($fournisseur);

        $purchasse->setIdContact($contact);
    
        $purchasse->setModePayment($mode_payment);
        $purchasse->setStatusPayment($status_payment);
        $purchasse->setTotalPaid($payment);
        
        $entityManager->persist($purchasse);
        
        $entityManager->flush();

        $purchasse->setNumFacture($this->number_format($purchasse->getId()));
        $entityManager->persist($purchasse);
        $entityManager->flush();

        
        //add product
        foreach($products as $product){
            $medicament=$productRepository->find($product['product_id']);

            $entityManager=$this->getDoctrine()->getManager();

            $transactionLine= new TransactionLine();
            $transactionLine->setIdTransaction($purchasse);
        $transactionLine->setIdMedicament($medicament);
        $transactionLine->setPrice($product['product_price_total']);
        $transactionLine->setQtyCarton($product['qty_carton']);
        $transactionLine->setQtyBoite($product['qty_boite']);
        $transactionLine->setQtyPlaquette($product['qty_plaquette']);

        $entityManager->persist($transactionLine);
        
        $entityManager->flush();
        //     $transactionLine=$transactionLineRepository->find($product);
        // $sell->addTransactionLine($transactionLine);

        }
        return $this->redirectToRoute('app_purchasse');
        }
    
      
    }

     /**
     * @Route("/purchasse/{id}", name="app_purchasse_show", methods={"GET"})
     */
    public function show(Transaction $purchasse,$id,TransactionRepository $transactionRepository ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $transaction=$transactionRepository->find($id);
    //    dd($transaction);
        return $this->render('purchasse/show.html.twig', [
            'purchasse' => $transaction,
        ]);
    }

     /**
     * @Route("/purchasse/{id}/edit", name="app_purchasse_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Transaction $purchasse,TransactionRepository $purchasseRepository,$id,ContactRepository $contactRepository,MedicamentRepository $productRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
    
     $all_purchasse=$purchasseRepository->find($id);
     $product=$productRepository->findAll();
    $qb = $contactRepository->createQueryBuilder('contact');
    $qb->where("contact.type = 'fournisseur'");
    $contact=$qb->getQuery()->getResult();

     //transaction line
     $transaction_lines=$all_purchasse->getTransactionLines();
     $count= count($transaction_lines);
     $status_payments=['paid','due'];
     $statuses=['final','draft'];
     $mode_payments=['cache','transaction1','transaction2'];
     return $this->render('purchasse/edit.html.twig', [
        'purchasse' => $all_purchasse,
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
     * @Route("/purchasse/update/id={id}", name="app_purchasse_udpate")
     */
    public function update(Request $request,$id,MedicamentRepository $productRepository ,TransactionRepository $purchasseRepository,ContactRepository $contactRepository)
    { 

        $date_tr=$request->get('date_transaction');
 
        $the_date=date('Y-m-d H:i:s',strtotime($date_tr));
        $date_transaction=new \DateTime($the_date);

        $total_amount=$request->get('total_amount');
        // $paid_on=$request->get('paid_on');

        $type=$request->get('type');
        $fournisseur=$request->get('contact');
        $status_payment=$request->get('status_payment');
        $final_total=$request->get('final_total');
        $status=$request->get('status');
        // $status_payment=$request->get('status_payment');
        $note=$request->get('note');
        $products=$request->get('product');
      
        //payment
        $mode_payment=$request->get('mode_payment');
        $payment=$request->get('payment');


        if (empty($date_transaction || $total_amount || $fournisseur)){
            return $this->redirectToRoute('app_purchasse');
            }
        
        else{
        $entityManager=$this->getDoctrine()->getManager();
        // $sell=new Transaction;
        $purchasse=$entityManager->getRepository(Transaction::class)->find($id);
        // $sell=$sellRepository->find($id);
        $purchasse->setDateTransaction($date_transaction);
        $purchasse->setPriceTotal($final_total);
        $purchasse->setType($type);
        $purchasse->setNote($note);
        $purchasse->setStatus($status);

        $contact=$contactRepository->find($fournisseur);

        $purchasse->setIdContact($contact);
    
        $purchasse->setModePayment($mode_payment);
        $purchasse->setStatusPayment($status_payment);
        $purchasse->setTotalPaid($payment);
     
        $entityManager->persist($purchasse);
        
        $entityManager->flush();

        $purchasse->setNumFacture($this->number_format($purchasse->getId()));
        $entityManager->persist($purchasse);
        $entityManager->flush();


        // removeTransactionLine
        foreach( $purchasse->getTransactionLines() as $tr_line){
        $tr_line_id=$tr_line->getId();
       
        $entityManager=$this->getDoctrine()->getManager();
        $purchasse_line=$entityManager->getRepository(TransactionLine::class)->find($tr_line_id);
        $entityManager->remove($purchasse_line);
        }
        
        //add product
        foreach($products as $product){
            $medicament=$productRepository->find($product['product_id']);

            $entityManager=$this->getDoctrine()->getManager();

            $transactionLine= new TransactionLine();
            $transactionLine->setIdTransaction($purchasse);
        $transactionLine->setIdMedicament($medicament);
        $transactionLine->setPrice($product['product_price_total']);
        $transactionLine->setQtyCarton($product['qty_carton']);
        $transactionLine->setQtyBoite($product['qty_boite']);
        $transactionLine->setQtyPlaquette($product['qty_plaquette']);

        $entityManager->persist($transactionLine);
        
        $entityManager->flush();
    
        }
    return $this->redirectToRoute('app_purchasse');
}    
        
 

    }

      /**
     * @Route("/purchasse/delete/{id}", name="app_purchasse_delete")
     */
    public function delete(Request $request, Transaction $purchasse,$id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

      //  if ($this->isCsrfTokenValid('delete'.$sell->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

        //remove sellLine
        foreach( $purchasse->getTransactionLines() as $tr_line){
        $tr_line_id=$tr_line->getId();
       
        $purchasse_line=$entityManager->getRepository(TransactionLine::class)->find($tr_line_id);
        $entityManager->remove($purchasse_line);
        }

            $entityManager->remove($purchasse);
            $entityManager->flush();

        return $this->redirectToRoute('app_purchasse');
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
        // $qb->where("medicament.name = 'ampicilline'");
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
            return $this->redirectToRoute('app_purchasse_create');
            
  
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
                return $this->render('purchasse/edit_medicament_table_row.html.twig', [
                    'row_index' => $index,
                    'i' => $row_index,
                    'product' => $product,
                ]);
            }

            return $this->render('purchasse/medicament_table_row.html.twig', [
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
