<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MedicamentRepository;
use App\Repository\StockRepository;
use App\Entity\BlocNote;
use App\Entity\Stock;
use App\Entity\Medicament;
use App\Form\MedicamentType;

class MedicamentController extends AbstractController
{
    /**
     * @Route("/medicament", name="app_medicament")
     */
    public function index(MedicamentRepository $medicamentRepository,Request $request): Response
    {
      $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
      $hasAccess = $this->isGranted('ROLE_ADMIN');
      $this->denyAccessUnlessGranted('ROLE_ADMIN');

      $term='';
      if ( $request->query->has('input_search')) {
        $term = $request->query->get('input_search');

        if (!empty($term)) {
          $result=$medicamentRepository->search_medicament($term);
   

      return $this->render('medicament/index.html.twig', [
        'medicament' => $result,
        'term' => $term
    ]);
    }
  }
      
      $all_medicament=$medicamentRepository->findAll();
        return $this->render('medicament/index.html.twig', [
            'medicament' => $all_medicament,
            'term' => $term,
        ]);
    }

     /**
     * @Route("/medicament/create", name="app_medicament_create")
     */
    public function create(Request $request,StockRepository $stockRepository): Response
    { 
      $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
      $hasAccess = $this->isGranted('ROLE_ADMIN');
      $this->denyAccessUnlessGranted('ROLE_ADMIN');

      $date=new \DateTime();
      $newDate = $date->format('Y-m-d');
      // dd($newDate);
        //  $the_date=date('Y-m-d H:i:s',$date);
      // $date_peremp=new \DateTime($the_date);
        $medicament=new Medicament();
        // $new=new \DateTime(ow);
        //$all_medicament=$medicamentRepository->findAll();
        $form = $this->createForm(MedicamentType::class, $medicament);
        $stock=$stockRepository->findAll();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($medicament);
            $entityManager->flush();

            return $this->redirectToRoute('app_medicament');
        }
        return $this->render('medicament/create.html.twig', [
            'medicament' => $medicament,
            'stock' => $stock,
            'date' => $newDate,
            'form' => $form->createView(),
        ]);

    }

      /**
     * @Route("/medicament/store", name="app_medicament_store")
     */
    public function store(Request $request,MedicamentRepository $productRepository ,StockRepository $stockRepository)
    { 
        try{

            $date_tr=$request->get('date_peremp');
 
        $the_date=date('Y-m-d H:i:s',strtotime($date_tr));
        $date_peremp=new \DateTime($the_date);

        $name=$request->get('name');
        $cible=$request->get('cible');
        $mode_conso=$request->get('mode_conso');
        $effet_secondaire=$request->get('effet_secondaire');
        $note=$request->get('note');
        $price_unit_carton=$request->get('price_unit_carton');
        $price_unit_boite=$request->get('price_unit_boite');
        $price_unit_plaquette=$request->get('price_unit_plaquette');
        $entityManager=$this->getDoctrine()->getManager();

        if (empty($name || $cible || $price_unit_carton||$price_unit_boite || $price_unit_plaquette)){
            return $this->redirectToRoute('app_medicament_create');
            }

        $medicament=new Medicament;
        
        $medicament->setName($name);
        $medicament->setCible($cible);
        $medicament->setModeConso($mode_conso);
        $medicament->setEffetSecondaire($effet_secondaire);
        $medicament->setNote($note);
        $medicament->setDatePeremp($date_peremp);
        $medicament->setPriceUnitPlaquette($price_unit_plaquette);
        $medicament->setPriceUnitCarton($price_unit_carton);
        $medicament->setPriceUnitBoite($price_unit_boite);

        $entityManager->persist($medicament);
        
        //  $entityManager->flush();

         $carton=$request->get('carton');
         $boite=$request->get('boite');
         $plaquette=$request->get('plaquette');

         if (empty($carton )){
            $carton=0;
            }
          if (empty($boite )){
            $boite=0;
            }
         if (empty($plaquette )){
            $plaquette=0;
            }

          $stock=new Stock;

          $stock->setCarton($carton);
          $stock->setBoite($boite);
          $stock->setplaquette($plaquette);
          $stock->setMedicament($medicament);
          $entityManager->persist($stock);
          $entityManager->flush();

          $output = ['success' => 1,
                            'msg' =>('medicament added successfully')
        ];
        return $this->redirectToRoute('app_medicament');
          } catch (\Exception $e) {
            // DB::rollBack();
            // \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());
            
            $output = ['success' => 0,
                            'msg' =>("something went wrong")
                        ];
                        return $this->redirectToRoute('app_medicament_create');
            
        }

    }

     /**
     * @Route("/medicament/{id}", name="app_medicament_show", methods={"GET"})
     */

    public function show(Medicament $medicament,$id,MedicamentRepository $medicamentRepository): Response
    {  
      $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
      $hasAccess = $this->isGranted('ROLE_ADMIN');
      $this->denyAccessUnlessGranted('ROLE_ADMIN');
      
      $the_medicament=$medicamentRepository->find($id);
        return $this->render('medicament/show.html.twig', [
            'medicament' => $the_medicament,
        ]);
    }

    
     /**
     * @Route("/medicament/{id}/edit", name="app_medicament_edit", methods={"GET","POST"})
     */
    public function edit(Request $request,MedicamentRepository $productRepository ,$id,StockRepository $stockRepository): Response
       {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

     $product=$productRepository->find($id);
   
     $all_product=$productRepository->findAll();
     return $this->render('medicament/edit.html.twig', [
        'medicament' => $product,
        'all_medicament' => $all_product
        ]);
    }


       /**
     * @Route("/medicament/update/id={id}", name="app_medicament_update")
     */
    public function update(Request $request,$id,MedicamentRepository $productRepository,StockRepository $stockRepository )
    { 
      $entityManager=$this->getDoctrine()->getManager();
      $medicament=$entityManager->getRepository(Medicament::class)->find($id);

    $route = $request->headers->get('referer');
      $date_tr=$request->get('date_peremp');
      $the_date=date('Y-m-d H:i:s',strtotime($date_tr));
      $date_peremp=new \DateTime($the_date);

      $name=$request->get('name');
      $cible=$request->get('cible');
      $mode_conso=$request->get('mode_conso');
      $effet_secondaire=$request->get('effet_secondaire');
      $note=$request->get('note');
      $price_unit_carton=$request->get('price_unit_carton');
      $price_unit_boite=$request->get('price_unit_boite');
      $price_unit_plaquette=$request->get('price_unit_plaquette');
      $entityManager=$this->getDoctrine()->getManager();

      if (empty($name || $cible || $price_unit_carton||$price_unit_boite || $price_unit_plaquette)){
        return $this->redirect($route);
          }

      $medicament->setName($name);
      $medicament->setCible($cible);
      $medicament->setModeConso($mode_conso);
      $medicament->setEffetSecondaire($effet_secondaire);
      $medicament->setNote($note);
      $medicament->setDatePeremp($date_peremp);
      $medicament->setPriceUnitPlaquette($price_unit_plaquette);
      $medicament->setPriceUnitCarton($price_unit_carton);
      $medicament->setPriceUnitBoite($price_unit_boite);

      $entityManager->persist($medicament);
      
      //  $entityManager->flush();
       $carton=$request->get('carton');
       $boite=$request->get('boite');
       $plaquette=$request->get('plaquette');
      
       if (empty($carton )){
        $carton=0;
        }
      if (empty($boite )){
        $boite=0;
        }
     if (empty($plaquette )){
        $plaquette=0;
        }
        $medicament=$entityManager->getRepository(Medicament::class)->find($id);
        $med=$medicament->getTheStock()->getId();
        $qb = $stockRepository->createQueryBuilder('stock');
       
        $qb->where("stock.id ='$med'");
        $stock=$qb->getQuery()->getResult();
        $stock[0]->setCarton($carton);
        $stock[0]->setBoite($boite);
        $stock[0]->setplaquette($plaquette);
        $stock[0]->setMedicament($medicament);
        $entityManager->persist($stock[0]);
        $entityManager->flush();

      return $this->redirectToRoute('app_medicament');
    }

      /**
     * @Route("/medicament/delete/{id}", name="app_medicament_delete")
     */
    public function delete(Request $request, Medicament $medicament,StockRepository $stockRepository ): Response
    {
      $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
      $hasAccess = $this->isGranted('ROLE_ADMIN');
      $this->denyAccessUnlessGranted('ROLE_ADMIN');
      
            $entityManager = $this->getDoctrine()->getManager();
            
          if(!empty($medicament->getTheStock())){
            $med=$medicament->getTheStock()->getId();
            $qb = $stockRepository->createQueryBuilder('stock');
            $qb->where("stock.id ='$med'");
            $stock=$qb->getQuery()->getResult();
            
            $entityManager->remove($stock[0]);
          }
            $entityManager->remove($medicament);
            $entityManager->flush();

        return $this->redirectToRoute('app_medicament');
    }
}
