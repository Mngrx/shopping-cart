<?php

namespace App\Repositories\Redis;

use App\Repositories\Interfaces\ShoppingCartRepositoryInterface;
use Illuminate\Support\Facades\Redis;

class ShoppingCartRepository implements ShoppingCartRepositoryInterface {

    private function generateRedisKey(int $key) {
        return 'sc:'.$key;
    }

    public function insertOrUpdate(int $key, array $data): string {
        Redis::set($this->generateRedisKey($key), json_encode($data));
        return $this->generateRedisKey($key);
    }
    
    public function getByKey(int $key): array {
        $shoppingCartData = Redis::get($this->generateRedisKey($key));
        if (!$shoppingCartData) {
            return [];
        }

        return (array) json_decode($shoppingCartData, true);
    }
    
    public function delete(int $key): string {        
        Redis::del($this->generateRedisKey($key));
        return $this->generateRedisKey($key);
    }

}