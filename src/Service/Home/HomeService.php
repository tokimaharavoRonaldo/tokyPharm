<?php
namespace App\Service\Home;

use App\Repository\TransactionRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class HomeService{
    protected $transactionRepository;
    protected $chartBuilder;
    public function __construct(TransactionRepository $transactionRepository,ChartBuilderInterface $chartBuilder)
    {
        $this->transactionRepository=$transactionRepository;
        $this->chartBuilder=$chartBuilder;
        
    }

public function getTransaction($date_start,$date_end,$value){
    $qb1 = $this->transactionRepository->createQueryBuilder('sell')
    ->where('sell.date_transaction BETWEEN :start AND :end')
    
    ->setParameter('start', $date_start)
    ->setParameter('end', $date_end)
    ->andWhere('sell.type = :val')
    ->setParameter('val', $value)

    
    ->select('SUM(sell.price_total) as sell_total',
    'SUM(sell.total_paid) as total_paid',
    'SUM(sell.price_total-sell.total_paid) as total_due',
    // 'MONTH(sell.date_transaction) as date_month'
    );
    $sell=$qb1->getQuery()->getResult();
    return $sell;
}

public function graph($date_start,$date_end){

    $qb_graph = $this->transactionRepository->createQueryBuilder('transaction')
    ->where('transaction.date_transaction BETWEEN :start AND :end')
    ->groupBy('date_month')
    ->setParameter('start', $date_start)
    ->setParameter('end', $date_end)
    
    ->select('SUM (case when transaction.type = :prc then transaction.price_total else 0 end ) as purchasse_total'
        ,'SUM (case when transaction.type = :s then transaction.price_total else 0 end ) as sell_total'
    
    ,'MONTH(transaction.date_transaction) as date_month'
    )
    // ->select('(sell_total - purchasse_total) as due_total')
    ->setParameter('prc', 'purchasse')
    ->setParameter('s', 'sell');
    
    $sell_graphs=$qb_graph->getQuery()->getResult();

    $arr=array();
    foreach ($sell_graphs as $sell_graph){

        // tokony tonga de atao le formule de ampiasaina ftsn aveo,formule benefice na perte
        array_push($arr,$sell_graph['sell_total'] - $sell_graph['purchasse_total']
    
    );
    }

        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => $arr,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);
        return $chart;
}

}
