<?php

namespace App\Services;

use App\Models\UseCase;

class UseCaseService
{
    /**
     * Store a new use case.
     */
    public function store(array $data): UseCase
    {
        return UseCase::create([
            'brand_id' => $data['brand_id'],
            'name'     => $data['name'],
            'status'   => $data['status'],
        ]);
    }

    /**
     * Update an existing use case.
     */
    public function update(UseCase $useCase, array $data): UseCase
    {
        $useCase->update([
            'brand_id' => $data['brand_id'],
            'name'     => $data['name'],
            'status'   => $data['status'],
        ]);

        return $useCase;
    }

    /**
     * Delete a use case.
     */
    public function delete(UseCase $useCase): void
    {
        $useCase->delete();
    }
}
