<?php

namespace App\Controller;

use App\Entity\Report;
use App\Entity\Transaction;
use Doctrine\Common\Collections\ArrayCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 */
class ReportController extends AbstractController
{
    protected function prepareReportData(array $tblData)
    {
        $returnData = [];
        foreach($tblData as $data){
            $key = $data->getTransactionCategory()->getTitle();
            if(!isset($returnData[$key])){
                $returnData[$key] = new Report();
                $returnData[$key]->setTitle($key);
                $returnData[$key]->setColor($data->getTransactionCategory()->getColor());
            }
            $returnData[$key]->addAmount($data->getCalculableAmount());
        }
        ksort($returnData);
        return $returnData;
    }

    protected function getReportData(bool $isOff, int $year, int $month): array
    {
        $data = [];

        if($year != 0){
            $startDate = new \DateTime();
            $startDate->setDate($year, ($month == 0? 1 : $month), 1);
            $startDate->setTime(0,0,0,0);

            $endDate = new \DateTime();
            $endDate->setDate($year, ($month == 0? 1 : $month), 1);
            $endDate->setTime(0,0,0,0);
            $endDate->modify('+ 1 '.($month == 0? 'year' : 'month').' - 1 second');

            return $this->getDoctrine()
                ->getRepository(Transaction::class)
                ->createQueryBuilder('t')
                ->where('t.isOff = :isOff')
                ->andWhere('t.date BETWEEN :dateFrom AND :dateTo')
                ->setParameters([
                    'isOff' => $isOff,
                    'dateFrom' => $startDate,
                    'dateTo' => $endDate,
                ])
                ->orderBy('t.date', 'ASC')
                ->getQuery()
                ->getResult();
        }

        return $this->getDoctrine()
            ->getRepository(Transaction::class)
            ->findBy(['isOff' => $isOff]);
    }

    /**
     * @Route("/admin/report", name="report")
     */
    public function report(Request $request, SessionInterface $session): Response
    {
        $year = $session->get('report-year') != null? $session->get('report-year') : 0;
        $month = $session->get('report-month') != null? $session->get('report-month') : 0;

        $outcomeData = $this->prepareReportData(
            $this->getReportData(true, $year, $month)
        );

        $incomeData = $this->prepareReportData(
            $this->getReportData(false, $year, $month)
        );

        $maxYear = new \DateTime();
        $maxYear = $maxYear->format('Y');

        $minYear = $this->getDoctrine()
            ->getRepository(Transaction::class)
            ->findBy([],['date' => 'ASC'], 1);
        if(isset($minYear[0])){
            $temp = $minYear[0]->getDate();
            $minYear = $temp->format('Y');
        }

        return $this->render('admin/report.html.twig', [
            'selectedYear' => $year,
            'selectedMonth' => $month,
            'minYear' => $minYear,
            'maxYear' => $maxYear,
            'outcomeData' => $outcomeData,
            'incomeData' => $incomeData,
        ]);
    }

    /**
     * @Route("/admin/report/year/{year}/month/{month}", name="report_session", methods={"GET"}))
     */
    public function reportSetSession(int $year, int $month, SessionInterface $session): JsonResponse
    {
        //TODO: set sessions
        $session->set('report-year', $year);
        $session->set('report-month', $month);

        $data = [
            'report-year' => $session->get('report-year'),
            'report-month' => $session->get('report-month'),
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }
}
