<?php


namespace App\Entity;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ReportFilter
{
    /**
     * private $year;
     * private $month;
     * private $account;
     * */

    private $session;

    public function __construct(SessionInterface $session){
        $this->session = $session;
    }

    public function getYearIsNull(){
        return $this->getYear() == 0;
    }
    public function getMonthIsNull(){
        return $this->getMonth() == 0;
    }
    public function getAccountIsNull(){
        return $this->getAccount() == 0;
    }

    public function setYear(int $year){
        $this->session->set('report-year', $year);
    }
    public function setMonth(int $month){
        $this->session->set('report-month', $month);
    }
    public function setAccount(int $account){
        $this->session->set('report-account', $account);
    }

    public function getYear(){
        return $this->session->get('report-year');
    }
    public function getMonth(){
        return $this->session->get('report-month');
    }
    public function getAccount(){
        return $this->session->get('report-account');
    }
}