<?php

namespace App\Exports;

use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class DoctorPatientsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, WithProperties, WithColumnFormatting, WithCustomStartCell
{
    protected $patients;
    
    public function __construct()
    {
        // Get patients for the currently logged-in doctor
        $doctor = Auth::user()->doctor;
        $patientIds = $doctor->appointments->pluck('patient_id')->unique();
        $this->patients = Patient::whereIn('id', $patientIds)->get();
    }
    
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->patients;
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return 'A5';
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'CIN',
            'NAME',
            'BIRTHDAY'
        ];
    }

    /**
     * @param mixed $patient
     * @return array
     */
    public function map($patient): array
    {
        return [
            $patient->id,
            $patient->cin ?? 'N/A',
            $patient->user->name,
            Carbon::parse($patient->birth_date)->format('M d, Y')
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'My Patients';
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    /**
     * @return array
     */
    public function properties(): array
    {
        return [
            'creator'        => 'QuickCare',
            'lastModifiedBy' => Auth::user()->name,
            'title'          => 'My Patients',
            'description'    => 'Doctor patients list exported from QuickCare',
            'subject'        => 'Patients List',
            'company'        => 'QuickCare',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Add title at the top
        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', 'QuickCare - My Patients');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '4F81BD'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        // Add generation date
        $sheet->mergeCells('A2:D2');
        $sheet->setCellValue('A2', 'Generated on: ' . Carbon::now()->format('F d, Y h:i A'));
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 10,
                'color' => ['rgb' => '808080'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);
        
        // Add doctor info
        $sheet->mergeCells('A3:D3');
        $doctorName = Auth::user()->name;
        $doctorSpecialty = Auth::user()->doctor->speciality ? Auth::user()->doctor->speciality->name : 'General';
        $sheet->setCellValue('A3', 'Doctor: ' . $doctorName . ' - ' . $doctorSpecialty);
        $sheet->getStyle('A3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
        ]);
        
        // Empty row before headers
        $sheet->mergeCells('A4:D4');
        
        // Get the last row after data
        $lastDataRow = $sheet->getHighestRow();
        
        // Style for headers
        $sheet->getStyle('A5:D5')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '4F81BD'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        // Alternate row colors for data rows
        for ($i = 6; $i <= $lastDataRow; $i++) {
            $fillColor = ($i % 2 == 0) ? 'DCE6F1' : 'FFFFFF';
            $sheet->getStyle('A' . $i . ':D' . $i)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $fillColor],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'B8CCE4'],
                    ],
                ],
            ]);
        }

        // Add borders to data cells
        $sheet->getStyle('A5:D' . $lastDataRow)->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '4F81BD'],
                ],
            ],
        ]);
        
        // Center ID, CIN and Birthday columns
        $sheet->getStyle('A6:A' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B6:B' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D6:D' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Left align Name column
        $sheet->getStyle('C6:C' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        
        // Vertical align center for all cells
        $sheet->getStyle('A6:D' . $lastDataRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        return [
            // Set row height for headers
            5 => ['font' => ['bold' => true]],
        ];
    }
} 