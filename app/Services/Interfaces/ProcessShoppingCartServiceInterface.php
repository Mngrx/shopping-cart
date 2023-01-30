<?php

namespace App\Services\Interfaces;

interface ProcessShoppingCartServiceInterface {

    public function checkout(): array;

}