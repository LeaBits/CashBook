<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Mobile_Detect;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultCrudController extends AbstractCrudController
{
    protected $pageSize;
    protected $sort;
    protected $detect;

    public function __construct()
    {
        $this->pageSize = 100;
        $this->sort = ['title' => 'ASC'];
        $this->detect = new Mobile_Detect();
    }

    protected function isOnMobile(): bool
    {
        if ($this->detect->isMobile() && !$this->detect->isTablet()) {
            return true;
        }
        return false;
    }

    public static function getEntityFqcn(): string
    {
        return "";
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPaginatorPageSize($this->pageSize)
            ->setDefaultSort($this->sort);
    }
}
