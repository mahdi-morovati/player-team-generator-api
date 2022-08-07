<?php

namespace App\Repositories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct()
    {
        $this->model = app($this->model());
    }

    abstract public function model();

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Player $player, array $data): bool
    {
        return $this->model->update($data);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

}
