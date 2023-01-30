<?php

namespace App\Services\Interfaces;

interface ProcessShoppingCartInterface {

    public function checkout(): float;

}