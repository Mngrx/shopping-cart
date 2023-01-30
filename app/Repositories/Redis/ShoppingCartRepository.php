<?php

namespace App\Repositories\Redis;

use App\Repositories\Interfaces\ShoppingCartRepositoryInterface;
use Illuminate\Support\Facades\Redis;

class ShoppingCartRepository implements ShoppingCartRepositoryInterface {

    private function generateRedisKey(int $key) {
        return 'sc_'.$key;
    }

    public function insertOrUpdate(int $key, array $data): string {
        Redis::set($this->generateRedisKey($key), $data);
        return $this->generateRedisKey($key);
    }
    
    public function getByKey(int $key): ?array {
        return Redis::get($this->generateRedisKey($key));
    }
    
    public function delete(int $key): string {        
        Redis::del($this->generateRedisKey($key));
        return $this->generateRedisKey($key);
    }

}