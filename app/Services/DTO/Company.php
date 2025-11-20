<?php

namespace App\Services\DTO;

use DateTime;

class Company
{
    public string $name;
    public string $address;
    public string $inn;
    public string $ogrn;
    public DateTime $regDate;

    public function __construct(string $name, string $address, string $inn, string $ogrn, DateTime $regDate)
    {
        $this->name = $name;
        $this->address = $address;
        $this->inn = $inn;
        $this->ogrn = $ogrn;
        $this->regDate = $regDate;
    }
}
