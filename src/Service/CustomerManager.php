<?php

declare(strict_types=1);

namespace App\Service;

class CustomerManager
{
    private $currentCustomer = null;

    public function getCurrentCustomer(): ?array
    {
        return $this->currentCustomer;
    }

    public function setCurrentCustomer(array $currentCustomer)
    {
        $this->currentCustomer = $currentCustomer;
    }
}
