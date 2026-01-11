<?php

namespace App\Services;

use App\Models\UseCase;

class UseCaseService
{
    public function store(array $data): UseCase
    {
        return UseCase::create([
            'brand_id' => $data['brand_id'],
            'name'     => $data['name'],
            'status'   => $data['status'],
        ]);
    }
    public function update(UseCase $useCase, array $data): UseCase
    {
        $useCase->update([
            'brand_id' => $data['brand_id'],
            'name'     => $data['name'],
            'status'   => $data['status'],
        ]);
        return $useCase;
    }
    public function delete(UseCase $useCase): void
    {
        $useCase->delete();
    }
}
