<?php

namespace App\Controller;

use App\Entity\BankAccount;
use App\Entity\Transaction;
use App\Entity\TransactionCategory;
use App\Entity\TransactionSubcategory;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);
        return $this->redirect($routeBuilder->setController(TransactionCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Cashbook');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Transactions');
        yield MenuItem::linktoDashboard('Transactions', 'fa fa-home');
        yield MenuItem::linktoRoute('Transaction report', 'fa fa-chart-bar', 'report');
        yield MenuItem::linkToCrud('Bank Accounts', 'far fa-credit-card', BankAccount::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-check', TransactionCategory::class);
        yield MenuItem::linkToCrud('Subcategories', 'fas fa-check-double', TransactionSubcategory::class);

        yield MenuItem::section('Users');
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
//        yield MenuItem::linkToLogout('Loguit', 'fa fa-exit');
    }
}
