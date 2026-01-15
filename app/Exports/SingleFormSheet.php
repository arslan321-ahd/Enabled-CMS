<?php

namespace App\Exports;

use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\Brand;
use App\Models\UseCase;
use App\Models\Tagging;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithTitle
};

class SingleFormSheet implements FromCollection, WithHeadings, WithTitle
{
    protected Form $form;
    protected array $filters;

    public function __construct(Form $form, array $filters = [])
    {
        $this->form = $form;
        $this->filters = $filters;
    }

    // Sheet name
    public function title(): string
    {
        return 'Form - ' . substr($this->form->title, 0, 25);
    }

    // Column headers
    public function headings(): array
    {
        $headings = [
            'Submitted At',
            'Name'
        ];

        foreach ($this->form->fields as $field) {
            $headings[] = $field->label;
        }

        return $headings;
    }

    // Data rows
    public function collection(): Collection
    {
        $query = FormSubmission::with(['values.field', 'form.user'])
            ->where('form_id', $this->form->id);

        // Date filters
        if (!empty($this->filters['start_date'])) {
            $query->whereDate('created_at', '>=', $this->filters['start_date']);
        }
        if (!empty($this->filters['end_date'])) {
            $query->whereDate('created_at', '<=', $this->filters['end_date']);
        }

        $submissions = $query->get();

        return $submissions->map(function ($submission) {
            $row = [
                $submission->created_at->format('Y-m-d H:i'),
                optional($submission->form->user)->name ?? 'Guest',
            ];

            $valuesMap = $submission->values->keyBy('form_field_id');

            foreach ($this->form->fields as $field) {
                $value = $valuesMap[$field->id]->value ?? '';

                // If field is checkbox
                if ($field->type === 'checkbox') {
                    $row[] = $value ? 'Yes' : 'No';
                    continue;
                }

                // If field is a data source, replace ID with name
                if ($field->data_source === 'brand' && $value) {
                    $brand = Brand::find($value);
                    $value = $brand ? $brand->name : $value;
                }

                if ($field->data_source === 'usecase' && $value) {
                    $usecase = UseCase::find($value);
                    $value = $usecase ? $usecase->name : $value;
                }

                if ($field->data_source === 'tagging' && $value) {
                    $tagging = Tagging::find($value);
                    $value = $tagging ? $tagging->source : $value;
                }

                $row[] = $value;
            }

            return collect($row);
        });
    }
}
