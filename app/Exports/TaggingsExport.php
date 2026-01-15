<?php

namespace App\Exports;

use App\Models\Tagging;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TaggingsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Tagging::query();

        // Apply filters
        $this->applyFilters($query);

        return $query->orderBy('source')->get();
    }

    private function applyFilters($query)
    {
        // Filter by date range
        if (isset($this->filters['start_date']) && !empty($this->filters['start_date'])) {
            $query->whereDate('created_at', '>=', $this->filters['start_date']);
        }

        if (isset($this->filters['end_date']) && !empty($this->filters['end_date'])) {
            $query->whereDate('created_at', '<=', $this->filters['end_date']);
        }

        // Filter by status - FIXED
        if (isset($this->filters['status']) && $this->filters['status'] !== '') {
            $query->where('status', $this->filters['status']);
        }
    }

    public function headings(): array
    {
        return [
            'ID',
            'Source',
            'Status',
            'Reference URL',
            'Created Date',
            'Updated Date'
        ];
    }

    public function map($tagging): array
    {
        return [
            $tagging->id,
            $tagging->source,
            ucfirst($tagging->status),
            $tagging->ref_url ?? 'N/A',
            $tagging->created_at->format('Y-m-d H:i:s'),
            $tagging->updated_at->format('Y-m-d H:i:s')
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
