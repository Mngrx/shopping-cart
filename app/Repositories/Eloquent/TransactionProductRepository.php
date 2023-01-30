<?php

namespace App\Repositories\Eloquent;

use App\Models\TransactionProduct;
use App\Repositories\Interfaces\TransactionProductRepositoryInterface;

class TransactionProductRepository extends BaseRepository implements TransactionProductRepositoryInterface
{
    public function __construct() {
        $this->model = app(TransactionProduct::class);
    }
}
