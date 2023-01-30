<?php

namespace App\Repositories\Interfaces;

interface CRUDRepositoryInterface {

    public function insert(array $data): int;
    public function get(int $id): array;
    public function getAll(): array;
    public function delete(int $id): bool;
    public function update(int $id, array $newData): bool;
    
}