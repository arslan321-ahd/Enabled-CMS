<?php

namespace App\Exports;

use App\Models\UseCase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UseCasesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = UseCase::with('brand:id,name');

        // Apply filters
        $this->applyFilters($query);

        return $query->orderBy('name')->get();
    }

    private function applyFilters($query)
    {
        // Filter by date range
        if (isset($this->filters['start_date']) && !empty($this->filters['start_date'])) {
            $query->whereDate('use_cases.created_at', '>=', $this->filters['start_date']);
        }

        if (isset($this->filters['end_date']) && !empty($this->filters['end_date'])) {
            $query->whereDate('use_cases.created_at', '<=', $this->filters['end_date']);
        }

        // Filter by brand_id from use_cases table
        if (isset($this->filters['brand_id']) && !empty($this->filters['brand_id'])) {
            $query->where('brand_id', $this->filters['brand_id']);
        }

        // Filter by status
        if (isset($this->filters['status']) && $this->filters['status'] !== '') {
            $query->where('status', $this->filters['status']);
        }
    }

    public function headings(): array
    {
        return [
            'ID',
            'Brand Name',
            'Use Case Name',
            'Status',
            'Created Date',
            'Updated Date'
        ];
    }

    public function map($useCase): array
    {
        return [
            $useCase->id,
            $useCase->brand->name ?? 'N/A',
            $useCase->name,
            ucfirst($useCase->status),
            $useCase->created_at->format('Y-m-d H:i:s'),
            $useCase->updated_at->format('Y-m-d H:i:s')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE0E0E0']
                ]
            ],
        ];
    }
}
