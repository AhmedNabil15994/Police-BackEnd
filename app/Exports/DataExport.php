<?php
namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class DataExport implements FromCollection,WithHeadings,WithMapping,WithColumnWidths
{

    public function __construct($data){
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'ID',
            'SKU',
            'NAME',
        ];
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->sku,
            isset($item->featured) ? $item->translations[0]->title : $item->title
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 50,
            'B' => 50,
            'C' => 100,
        ];
    }
}
