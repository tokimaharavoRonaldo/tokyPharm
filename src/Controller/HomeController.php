<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use DatePeriod;
use DateInterval;
use Symfony\Component\Security\Core\Security;

use App\Repository\UserRepository;


use App\Repository\TransactionRepository;
use App\Service\Home\HomeService;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class HomeController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }




/**
     * @Route("/", name="app_home")
     */
    public function index(UserRepository $userRepository,ChartBuilderInterface $chartBuilder,TransactionRepository $transactionRepository,Request $request,HomeService $homeService): Response  
    {
       
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
   

        $session =$request->getSession();
    
        $date=new \DateTime();
        $date_start = $date->format('m-d-Y');
        $date_end = $date->format('m-d-Y');

        // $term = $request->query->get('term');
        if(!empty($request->query->get('start'))){
            $date_start = $request->query->get('start');
            $date_end = $request->query->get('end');
        }
        

        $sell=$homeService->getTransaction($date_start,$date_end,$value='sell');
        $purchasse=$homeService->getTransaction($date_start,$date_end,$value='purchasse');
        
   
        // $entityManager = $this->getDoctrine()->getManager();

    //for graph

    //where year year actually

    $chart=$homeService->graph($date_start,$date_end);


        return $this->render('home/index.html.twig', [
            'chart' => $chart,
            'date_start' => $date_start,
            'date_end' => $date_end,
            'sell' => $sell,
            'purchasse' => $purchasse,
        ]);    
    }

    
}
