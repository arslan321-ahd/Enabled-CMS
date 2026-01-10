<?php

namespace App\Observers;

use App\Models\Brand;
use App\Services\LogService;

class BrandObserver
{
    public function created(Brand $brand)
    {
        LogService::create(
            'New Brand Created',
            "Brand '{$brand->name}' was created",
            'created',
            $brand
        );
    }

    public function updated(Brand $brand)
    {
        if ($brand->wasChanged('status')) {
            LogService::create(
                'Brand Status Changed',
                "Brand '{$brand->name}' status changed to '{$brand->status}'",
                'status_changed',
                $brand
            );
        } else {
            LogService::create(
                'Brand Updated',
                "Brand '{$brand->name}' was updated",
                'updated',
                $brand
            );
        }
    }

    public function deleted(Brand $brand)
    {
        LogService::create(
            'Brand Deleted',
            "Brand '{$brand->name}' was deleted",
            'deleted',
            $brand
        );
    }
}
