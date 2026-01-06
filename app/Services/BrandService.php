<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Support\Facades\Storage;

class BrandService
{
    public function store(array $data): Brand
    {
        if (isset($data['logo'])) {
            $data['logo'] = $data['logo']->store('brands', 'public');
        }

        return Brand::create([
            'name'          => $data['name'],
            'logo'          => $data['logo'],
            'status'        => $data['status'],
            'reference_url' => $data['reference_url'] ?? null,
        ]);
    }


    public function update(Brand $brand, array $data): Brand
    {
        if (isset($data['logo'])) {
            // Delete old logo if exists
            if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
                Storage::disk('public')->delete($brand->logo);
            }
            $data['logo'] = $data['logo']->store('brands', 'public');
        } else {
            $data['logo'] = $brand->logo;
        }

        $brand->update([
            'name'          => $data['name'],
            'logo'          => $data['logo'],
            'status'        => $data['status'],
            'reference_url' => $data['reference_url'] ?? null,
        ]);

        return $brand;
    }
    public function delete(Brand $brand): void
    {
        if ($brand->logo && Storage::disk('public')->exists($brand->logo)) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();
    }
}
