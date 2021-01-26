<?php

namespace App\Controller;

use App\Controller\Crud\_DefaultCrudController;
use App\Entity\BankAccount;
use App\Entity\Transaction;
use App\Entity\TransactionCategory;
use App\Entity\TransactionSubcategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class TransactionCrudController extends DefaultCrudController
{
    public function __construct()
    {
        parent::__construct();

        $this->sort = ['date' => 'DESC'];
    }

    public static function getEntityFqcn(): string
    {
        return Transaction::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // id field
        yield IdField::new('id')
            ->hideOnForm()
            ->hideOnIndex();

        // bank account field, ordered
        $bankAccounts = $this->getDoctrine()
            ->getRepository(BankAccount::class)
            ->createQueryBuilder('b')
            ->orderBy('b.title', 'ASC')
            ->getQuery()
            ->getResult();
        $account = AssociationField::new('bankAccount', 'Bank Account');
        if($this->isOnMobile()){ $account->hideOnIndex(); }
        yield $account->setFormTypeOptions(["choices" => $bankAccounts])
            ->setTemplatePath('admin/colorBadgeField.html.twig')
            ->setRequired(true)
            ->setSortable(false);

        // is off field
        $isOff = BooleanField::new('isOff', 'Taken off');
        if($this->isOnMobile()){ $isOff->hideOnIndex(); }
        yield $isOff->setSortable(false)
            ->renderAsSwitch(false);

        // amount field
        yield MoneyField::new('amount', '€')
            ->setCurrency('EUR');

        // date field
        yield DateField::new('date', 'Date')
            ->setFormat('d/M/y');

        // categories field, ordered
        $categories = $this->getDoctrine()
            ->getRepository(TransactionCategory::class)
            ->createQueryBuilder('tc')
            ->orderBy('tc.title', 'ASC')
            ->getQuery()
            ->getResult();
        yield AssociationField::new('transactionCategory', 'Category')
            ->setFormTypeOptions(["choices" => $categories])
            ->setTemplatePath('admin/colorBadgeField.html.twig')
            ->setRequired(true)
            ->setSortable(false);

        // subcategories field, ordered
        $subCategories = $this->getDoctrine()
            ->getRepository(TransactionSubcategory::class)
            ->createQueryBuilder('tc')
            ->orderBy('tc.title', 'ASC')
            ->getQuery()
            ->getResult();
        $subCategory = AssociationField::new('transactionSubcategory', 'Subcategory');
        if($this->isOnMobile()){ $subCategory->hideOnIndex(); }
        yield $subCategory->setSortable(false)
            ->setFormTypeOptions(["choices" => $subCategories]);

        // repayment field, only show incoming transactions
        $repayTransactions = $this->getDoctrine()->getRepository(Transaction::class)
            ->findBy(['isOff' => 0], ['date' => 'DESC']);
        $repayment = AssociationField::new('repaymentTransaction', 'Repaid by transaction');
        if($this->isOnMobile()){ $repayment->hideOnIndex(); }
        yield $repayment->setFormTypeOptions(["choices" => $repayTransactions])
            ->setTemplatePath('admin/repaidField.html.twig')
            ->setSortable(false);

        // comments field
        yield TextEditorField::new('comments', 'Comments')
            ->setSortable(false)
            ->hideOnIndex();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('bankAccount')
            ->add('date')
            ->add('isOff')
            ->add('transactionCategory')
            ->add('transactionSubcategory')
            ;
    }
}
