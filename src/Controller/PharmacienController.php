<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClientRepository;
use App\Repository\ContactRepository;
use App\Entity\BlocNote;
use App\Entity\Client;
use App\Entity\Contact;
use App\Form\MedicamentType;
use App\Form\ContactType;

class PharmacienController extends AbstractController
{
/**
     * @Route("/pharmacien", name="app_pharmacien")
     */
    public function index(ContactRepository $pharmacienRepository): Response  
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $pharmacienRepository->createQueryBuilder('cl');
        // $qb->select('cl.type');
        $qb->where("cl.type = 'pharmacien'");
        // dd($qb->getQuery()->getResult());
        $result=$qb->getQuery()->getResult();
         $all_client= $pharmacienRepository->findAll();
        //  $all_client= Contact->where('type','client');
        return $this->render('pharmacien/index.html.twig', [
            'pharmacien' => $result,
        ]);
    }

     /**
     * @Route("/pharmacien/create", name="app_pharmacien_create")
     */
    public function create(Request $request): Response
    { 
        $pharmacien=new Contact();
        //$all_medicament=$medicamentRepository->findAll();
        $form = $this->createForm(ContactType::class, $pharmacien);
        $form->handleRequest($request);
        // dd($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pharmacien);
            $entityManager->flush();

            return $this->redirectToRoute('app_pharmacien');
        }
        return $this->render('pharmacien/create.html.twig', [
            'pharmacien' => $pharmacien,
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/pharmacien/{id}", name="app_pharmacien_show", methods={"GET"})
     */
 
    public function show(Contact $client,$id,ContactRepository $pharmacienRepository): Response
    {  $the_pharmacien=$pharmacienRepository->find($id);
        return $this->render('pharmacien/show.html.twig', [
            'pharmacien' => $the_pharmacien,
        ]);
    }

     /**
     * @Route("/pharmacien/{id}/edit", name="app_pharmacien_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Contact $pharmacien,$id,ContactRepository $pharmacienRepository): Response
    {
        $the_pharmacien=$pharmacienRepository->find($id);
        $form = $this->createForm(ContactType::class, $the_pharmacien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_pharmacien');
        }

        return $this->render('pharmacien/edit.html.twig', [
            // 'client' => $client,
            'form' => $form->createView(),
        ]);
    }

      /**
     * @Route("/pharmacien/delete/{id}", name="app_pharmacien_delete")
     */
    public function delete(Request $request, Contact $pharmacien): Response
    {
        // if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pharmacien);
            $entityManager->flush();
        // }

        return $this->redirectToRoute('app_pharmacien');
    }
}
