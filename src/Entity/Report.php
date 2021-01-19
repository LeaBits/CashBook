<?php


namespace App\Entity;


use phpDocumentor\Reflection\Types\Void_;

class Report
{
    private $title;
    private $color;
    private $amount = 0;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color)
    {
        $this->color = $color;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function addAmount(int $amount)
    {
        $this->amount += $amount;
    }
}