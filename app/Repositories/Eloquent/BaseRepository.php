<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository {

    protected Model $model;

    public function insert(array $data): int {
        $insertedData = $this->model->create($data);
        
        return $insertedData->id;

    }

    public function get(int $id): array {

        return $this->model->find($id)->toArray();

    }

    public function getAll(): array {

        return $this->model->all()->toArray();

    }

    public function delete(int $id): bool {
        
        $this->model->destroy($id);
        return true;
    }
    
    public function update(int $id, array $newData): bool {

        $this->model->whereId($id)->update($newData);
        return true;
    }


}