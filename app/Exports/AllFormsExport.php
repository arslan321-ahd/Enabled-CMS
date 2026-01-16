<?php

namespace App\Exports;

use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithTitle
};
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AllFormsExport implements FromCollection, WithHeadings
{
    protected array $filters;
    protected array $rows = [];
    protected array $headings = [];

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function headings(): array
    {
        return [];
    }

    public function collection()
    {
        $this->rows = [];

        $formsQuery = Form::with(['fields', 'user'])
            ->where('active', true)
            ->orderBy('id');
        if (!empty($this->filters['form_id']) && $this->filters['form_id'] !== 'all') {
            $formsQuery->where('id', $this->filters['form_id']);
        }
        $forms = $formsQuery->get();
        foreach ($forms as $form) {
            $this->rows[] = [$form->title];
            $headings = ['Submitted At', 'Name'];
            foreach ($form->fields as $field) {
                $headings[] = $field->label;
            }
            $this->rows[] = $headings;
            $submissions = FormSubmission::with('values', 'user')
                ->where('form_id', $form->id);
            if (!empty($this->filters['start_date'])) {
                $submissions->whereDate('created_at', '>=', $this->filters['start_date']);
            }
            if (!empty($this->filters['end_date'])) {
                $submissions->whereDate('created_at', '<=', $this->filters['end_date']);
            }
            $submissions = $submissions->get();
            foreach ($submissions as $submission) {
                $row = [];
                $row[] = $submission->created_at->format('Y-m-d H:i');
                $row[] = optional($submission->user)->name ?? 'Guest';
                $valuesMap = $submission->values->keyBy('form_field_id');

                foreach ($form->fields as $field) {
                    $value = $valuesMap[$field->id]->value ?? '';
                    if ($field->data_source) {
                        switch ($field->data_source) {
                            case 'brand':
                                $value = \App\Models\Brand::find($value)?->name ?? $value;
                                break;
                            case 'usecases':
                                $value = \App\Models\UseCase::find($value)?->name ?? $value;
                                break;
                            case 'tagging':
                                $value = \App\Models\Tagging::find($value)?->source ?? $value;
                                break;
                        }
                    }
                    if ($field->type === 'checkbox') {
                        $value = ($value == '1' || strtolower($value) === 'yes') ? 'Yes' : 'No';
                    }

                    $row[] = $value;
                }

                $this->rows[] = $row;
            }
            $this->rows[] = [];
        }
        return collect($this->rows);
    }
}
