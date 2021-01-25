<?php

namespace App\Controller;

use App\Entity\BankAccount;
use App\Entity\Report;
use App\Entity\ReportFilter;
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
    private $reportFilter;

    private function prepareReportData(array $tblData)
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

    private function getReportData(bool $isOff): array
    {
        if(!$this->reportFilter->getYearIsNull()){
            $startDate = new \DateTime();
            $startDate->setDate(
                $this->reportFilter->getYear(),
                ($this->reportFilter->getMonthIsNull()? 1 : $this->reportFilter->getMonth()),
                1
            );
            $startDate->setTime(0,0,0,0);

            $endDate = new \DateTime();
            $endDate->setDate(
                $this->reportFilter->getYear(),
                ($this->reportFilter->getMonthIsNull()? 1 : $this->reportFilter->getMonth()),
                1
            );
            $endDate->setTime(0,0,0,0);
            $endDate->modify('+ 1 '
                .($this->reportFilter->getMonthIsNull()? 'year' : 'month')
                .' - 1 second');

            $query = $this->getDoctrine()
                ->getRepository(Transaction::class)
                ->createQueryBuilder('t')
                ->where('t.isOff = :isOff')
                ->andWhere('t.date BETWEEN :dateFrom AND :dateTo')
                ->setParameters([
                    'isOff' => $isOff,
                    'dateFrom' => $startDate,
                    'dateTo' => $endDate,
                ]);

            if(!$this->reportFilter->getAccountIsNull()){
                $query->andWhere('t.bank_account_id = :account')
                    ->setParameter('account', $this->reportFilter->getAccount());
            }

            return $query->orderBy('t.date', 'ASC')
                ->getQuery()
                ->getResult();
        }

        $findBy = ['isOff' => $isOff];
        if(!$this->reportFilter->getAccountIsNull()){
            $findBy['bankAccount'] = $this->reportFilter->getAccount();
        }
        return $this->getDoctrine()
            ->getRepository(Transaction::class)
            ->findBy($findBy);
    }

    /**
     * @Route("/admin/report", name="report")
     */
    public function report(Request $request, SessionInterface $session): Response
    {
        $this->reportFilter = new ReportFilter($session);

        $outcomeData = $this->prepareReportData(
            $this->getReportData(true)
        );

        $incomeData = $this->prepareReportData(
            $this->getReportData(false)
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

        $accounts = $this->getDoctrine()
            ->getRepository(BankAccount::class)
            ->findAll();

        return $this->render('admin/report.html.twig', [
            'minYear' => $minYear,
            'maxYear' => $maxYear,
            'accounts' => $accounts,
            'selectedYear' => $this->reportFilter->getYear(),
            'selectedMonth' => $this->reportFilter->getMonth(),
            'selectedAccount' => $this->reportFilter->getAccount(),
            'outcomeData' => $outcomeData,
            'incomeData' => $incomeData,
        ]);
    }

    /**
     * @Route("/admin/report/year/{year}", name="report_session_year", methods={"GET"}))
     */
    public function reportSetSessionYear(int $year): JsonResponse
    {
        $this->reportFilter->setYear($year != null? $year : 0);
        return new JsonResponse([
            'report-year' => $this->reportFilter->getYear()
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/admin/report/month/{month}", name="report_session_month", methods={"GET"}))
     */
    public function reportSetSessionMonth(int $month): JsonResponse
    {
        $this->reportFilter->setMonth($month != null? $month : 0);
        return new JsonResponse([
            'report-month' => $this->reportFilter->getMonth()
        ], Response::HTTP_OK);
    }

    /**
     * @Route("/admin/report/account/{account}", name="report_session_account", methods={"GET"}))
     */
    public function reportSetSessionAccount(int $account): JsonResponse
    {
        $this->reportFilter->setAccount($account != null? $account : 0);
        return new JsonResponse([
            'report-account' => $this->reportFilter->getAccount()
        ], Response::HTTP_OK);
    }
}
