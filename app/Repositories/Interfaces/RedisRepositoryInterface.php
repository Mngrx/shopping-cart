<?php

namespace App\Repositories\Interfaces;

interface RedisRepositoryInterface {

    public function insertOrUpdate(int $key, array $data): string;
    public function getByKey(int $key): array;
    public function delete(int $key): string;

}