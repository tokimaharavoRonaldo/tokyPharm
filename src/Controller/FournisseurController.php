<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClientRepository;
use App\Repository\ContactRepository;
use App\Entity\Client;
use App\Entity\Contact;
use App\Form\MedicamentType;
use App\Form\ContactType;

class FournisseurController extends AbstractController
{
/**
     * @Route("/fournisseur", name="app_fournisseur")
     */
    public function index(ContactRepository $fournisseurRepository,Request $request): Response  
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $term='';
        $type='fournisseur';
        if ( $request->query->has('input_search')) {
            $term = $request->query->get('input_search');
        if (!empty($term)) {
            $result=$fournisseurRepository->search($term,$type);
           
          //  dd($result[4]->getIdContact()->getName());
    
          return $this->render('fournisseur/index.html.twig', [
            'fournisseur' => $result,
            'term' => $term
        ]);
        }
    }

        $em = $this->getDoctrine()->getManager();
        $qb = $fournisseurRepository->createQueryBuilder('cl');
        // $qb->select('cl.type');
        $qb->where("cl.type = 'fournisseur'");
        // dd($qb->getQuery()->getResult());
        $result=$qb->getQuery()->getResult();
         $all_client= $fournisseurRepository->findAll();
        //  $all_client= Contact->where('type','client');
        return $this->render('fournisseur/index.html.twig', [
            'fournisseur' => $result,
            'term' => $term
        ]);
    }

     /**
     * @Route("/fournisseur/create", name="app_fournisseur_create")
     */
    public function create(Request $request): Response
    { 
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $fournisseur=new Contact();
        //$all_medicament=$medicamentRepository->findAll();
        $form = $this->createForm(ContactType::class, $fournisseur, array('attr' => array('class' => 'form_contact')));
        $form->handleRequest($request);
        // dd($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($fournisseur);
            $entityManager->flush();

            return $this->redirectToRoute('app_fournisseur');
        }
        return $this->render('fournisseur/create.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/fournisseur/{id}", name="app_fournisseur_show", methods={"GET"})
     */


    public function show(Contact $fournisseur,$id,ContactRepository $fournisseurRepository): Response
    { 
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $the_fournisseur=$fournisseurRepository->find($id);
        return $this->render('fournisseur/show.html.twig', [
            'fournisseur' => $the_fournisseur,
        ]);
    }

     /**
     * @Route("/fournisseur/{id}/edit", name="app_fournisseur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Contact $fournisseur,$id,ContactRepository $fournisseurRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $the_fournisseur=$fournisseurRepository->find($id);
        $form = $this->createForm(ContactType::class, $the_fournisseur, array('attr' => array('class' => 'form_contact')));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_fournisseur');
        }

        return $this->render('fournisseur/edit.html.twig', [
            // 'client' => $client,
            'form' => $form->createView(),
        ]);
    }

      /**
     * @Route("/fournisseur/delete/{id}", name="app_fournisseur_delete")
     */
    public function delete(Request $request, Contact $fournisseur): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($fournisseur);
            $entityManager->flush();
        // }

        return $this->redirectToRoute('app_fournisseur');
    }
}
