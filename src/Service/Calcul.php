<?php
namespace App\Service;

class Calcul {

    public float $taux;

    public function __construct($taux)
    {
     $this->taux = $taux;   
    }

    function calculPrixTTC (float $price) : float
    {
        return $price + $price * $this->taux;
    }

}