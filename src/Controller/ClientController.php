<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClientRepository;
use App\Repository\ContactRepository;
use App\Entity\Contact;
use App\Entity\Client;
use App\Form\MedicamentType;
use App\Form\ContactType;

class ClientController extends AbstractController
{
/**
     * @Route("/client", name="app_client")
     */
    public function index(ContactRepository $clientRepository,Request $request): Response  
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // $hasAccess = $this->isGranted('ROLE_ADMIN');
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $term='';
        $type='client';
        if ( $request->query->has('input_search')) {
            $term = $request->query->get('input_search');
            if (!empty($term)) {
                $result=$clientRepository->search($term,$type);
      
    
          return $this->render('client/index.html.twig', [
            'client' => $result,
            'term' => $term
        ]);
        }
    }

        $qb = $clientRepository->createQueryBuilder('cl');
        $qb->where("cl.type = 'client'");
        $result=$qb->getQuery()->getResult();
        return $this->render('client/index.html.twig', [
            'client' => $result,
            'term' => $term
        ]);
    }

     /**
     * @Route("/client/create", name="app_client_create")
     */
    public function create(Request $request): Response
    { 
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // $hasAccess = $this->isGranted('ROLE_ADMIN');
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $client=new Contact();
        //$all_medicament=$medicamentRepository->findAll();
        $form = $this->createForm(ContactType::class, $client, array('attr' => array('class' => 'form_contact')));
        $form->handleRequest($request);
        // dd($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_client');
        }
        return $this->render('client/create.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/client/{id}", name="app_client_show", methods={"GET"})
     */
    public function show(Contact $client,$id,ContactRepository $clientRepository): Response
    {  
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // $hasAccess = $this->isGranted('ROLE_ADMIN');
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $the_client=$clientRepository->find($id);
        return $this->render('client/show.html.twig', [
            'client' => $the_client,
        ]);
    }

     /**
     * @Route("/client/{id}/edit", name="app_client_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Contact $client,$id,ContactRepository $clientRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $the_client=$clientRepository->find($id);
        $form = $this->createForm(ContactType::class, $the_client, array('attr' => array('class' => 'form_contact')));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_client');
        }

        return $this->render('client/edit.html.twig', [
            // 'client' => $client,
            'form' => $form->createView(),
        ]);
    }

      /**
     * @Route("/client/delete/{id}", name="app_client_delete")
     */
    public function delete(Request $request, Contact $client): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        // if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($client);
            $entityManager->flush();
        // }

        return $this->redirectToRoute('app_client');
    }
}
