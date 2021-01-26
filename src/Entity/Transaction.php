<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $amount;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isOff = true;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity=BankAccount::class, inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bankAccount;

    /**
     * @ORM\ManyToOne(targetEntity=TransactionCategory::class, inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $transactionCategory;

    /**
     * @ORM\ManyToOne(targetEntity=TransactionSubcategory::class, inversedBy="transactions")
     */
    private $transactionSubcategory;

    /**
     * @ORM\OneToOne(targetEntity=Transaction::class, inversedBy="primaryTransaction", cascade={"persist", "remove"})
     */
    private $repaymentTransaction;

    /**
     * @ORM\OneToOne(targetEntity=Transaction::class, mappedBy="repaymentTransaction", cascade={"persist", "remove"})
     */
    private $primaryTransaction;

    public function __toString()
    {
        $amount = $this->getCalculableAmount();
        $date = $this->getDate()->format('d/m/y');
        $category = $this->getTransactionCategory();
        $subcategory = $this->getTransactionSubcategory();

        return $category
            .(!empty($subcategory)? ' '.$subcategory : '')
            .' '.$date.' € '.$amount;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?string
    {
        if(!empty($this->getRepaymentTransaction())){
            return ($this->amount - $this->getRepaymentTransaction()->getAmount());
        }
        return $this->amount;
    }

    public function getCalculableAmount(): float
    {
        $amount = floatval($this->getAmount());
        return $amount / 100;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIsOff(): ?bool
    {
        return $this->isOff;
    }

    public function setIsOff(bool $isOff): self
    {
        $this->isOff = $isOff;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getBankAccount(): ?BankAccount
    {
        return $this->bankAccount;
    }

    public function setBankAccount(?BankAccount $bankAccount): self
    {
        $this->bankAccount = $bankAccount;

        return $this;
    }

    public function getTransactionCategory(): ?TransactionCategory
    {
        return $this->transactionCategory;
    }

    public function setTransactionCategory(?TransactionCategory $transactionCategory): self
    {
        $this->transactionCategory = $transactionCategory;

        return $this;
    }

    public function getTransactionSubcategory(): ?TransactionSubcategory
    {
        return $this->transactionSubcategory;
    }

    public function setTransactionSubcategory(?TransactionSubcategory $transactionSubcategory): self
    {
        $this->transactionSubcategory = $transactionSubcategory;

        return $this;
    }

    public function getRepaymentTransaction(): ?self
    {
        return $this->repaymentTransaction;
    }

    public function setRepaymentTransaction(?self $repaymentTransaction): self
    {
        $this->repaymentTransaction = $repaymentTransaction;

        return $this;
    }

    public function getPrimaryTransaction(): ?self
    {
        return $this->primaryTransaction;
    }

    public function setPrimaryTransaction(?self $primaryTransaction): self
    {
        // unset the owning side of the relation if necessary
        if ($primaryTransaction === null && $this->primaryTransaction !== null) {
            $this->primaryTransaction->setRepaymentTransaction(null);
        }

        // set the owning side of the relation if necessary
        if ($primaryTransaction !== null && $primaryTransaction->getRepaymentTransaction() !== $this) {
            $primaryTransaction->setRepaymentTransaction($this);
        }

        $this->primaryTransaction = $primaryTransaction;

        return $this;
    }

}
