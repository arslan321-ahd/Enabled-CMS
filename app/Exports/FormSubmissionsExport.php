<?php

namespace App\Exports;

use App\Models\Form;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FormSubmissionsExport implements WithMultipleSheets
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Get all active forms or specific form
        $formsQuery = Form::with(['fields' => fn($q) => $q->orderBy('order')])
            ->where('active', true);

        if (!empty($this->filters['form_id']) && $this->filters['form_id'] !== 'all') {
            $formsQuery->where('id', $this->filters['form_id']);
        }

        $forms = $formsQuery->get();

        // Create a sheet for each form
        foreach ($forms as $form) {
            $sheets[] = new \App\Exports\SingleFormSheet($form, $this->filters);
        }

        return $sheets;
    }
}
