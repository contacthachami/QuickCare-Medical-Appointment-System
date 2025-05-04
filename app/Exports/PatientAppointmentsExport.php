<?php

namespace App\Exports;

use App\Models\Appointment;
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

class PatientAppointmentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, WithProperties, WithColumnFormatting, WithCustomStartCell
{
    protected $appointments;
    
    public function __construct()
    {
        $this->appointments = Appointment::where('patient_id', Auth::user()->patient->id)
            ->with(['doctor.user', 'schedule'])
            ->orderBy('appointment_date', 'desc')
            ->get();
    }
    
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->appointments;
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
            'DATE',
            'DOCTOR',
            'REASON',
            'STATUS', 
            'STARTING HOUR'
        ];
    }

    /**
     * @param mixed $appointment
     * @return array
     */
    public function map($appointment): array
    {
        // Format appointment date
        $date = Carbon::parse($appointment->appointment_date)->format('M d, Y');
        $time = $appointment->schedule ? $appointment->schedule->start : Carbon::parse($appointment->appointment_date)->format('H:i:s');
        
        // Format doctor name and specialty
        $doctorName = $appointment->doctor->user->name;
        $doctorSpecialty = $appointment->doctor->speciality ? $appointment->doctor->speciality->name : 'General';
        
        return [
            $appointment->id,
            $date . "\n" . $time,
            $doctorName . "\n" . $doctorSpecialty,
            $appointment->reason,
            $appointment->status,
            $time
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'My Appointments';
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DATETIME,
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
            'title'          => 'My Appointments',
            'description'    => 'Patient appointments exported from QuickCare',
            'subject'        => 'Medical Appointments',
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
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'QuickCare - My Appointments');
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
        $sheet->mergeCells('A2:F2');
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
        
        // Add patient info
        $sheet->mergeCells('A3:F3');
        $patientName = Auth::user()->name;
        $sheet->setCellValue('A3', 'Patient: ' . $patientName);
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
        $sheet->mergeCells('A4:F4');
        
        // Get the last row after data
        $lastDataRow = $sheet->getHighestRow();
        
        // Style for headers
        $sheet->getStyle('A5:F5')->applyFromArray([
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
            $sheet->getStyle('A' . $i . ':F' . $i)->applyFromArray([
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
        $sheet->getStyle('A5:F' . $lastDataRow)->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '4F81BD'],
                ],
            ],
        ]);
        
        // Enable text wrapping for all data cells
        $sheet->getStyle('A6:F' . $lastDataRow)->getAlignment()->setWrapText(true);
        
        // Set row height for data cells to accommodate multiple lines
        for ($i = 6; $i <= $lastDataRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(40);
        }

        // Center ID and Status columns
        $sheet->getStyle('A6:A' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E6:F' . $lastDataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Vertical align center for all cells
        $sheet->getStyle('A6:F' . $lastDataRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Apply color coding based on status
        for ($i = 6; $i <= $lastDataRow; $i++) {
            $status = $sheet->getCell('E' . $i)->getValue();
            
            // Set color based on status
            if ($status == 'Approved') {
                $sheet->getStyle('E' . $i)->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => '006100'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'C6EFCE'],
                    ],
                ]);
            } elseif ($status == 'Pending') {
                $sheet->getStyle('E' . $i)->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => '9C5700'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFEB9C'],
                    ],
                ]);
            } elseif ($status == 'Cancelled' || $status == 'Canceled') {
                $sheet->getStyle('E' . $i)->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => '9C0006'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFC7CE'],
                    ],
                ]);
            }
        }
        
        // Add summary after the data
        $summaryRow = $lastDataRow + 2;
        $sheet->setCellValue('A' . $summaryRow, 'APPOINTMENT SUMMARY');
        $sheet->mergeCells('A' . $summaryRow . ':F' . $summaryRow);
        $sheet->getStyle('A' . $summaryRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => '4F81BD'],
            ],
        ]);
        
        // Get counts directly from the appointments collection
        $totalAppointments = $this->appointments->count();
        $approvedCount = $this->appointments->where('status', 'Approved')->count();
        $pendingCount = $this->appointments->where('status', 'Pending')->count();
        $canceledCount = $this->appointments->whereIn('status', ['Cancelled', 'Canceled'])->count();
        
        // Total appointments
        $summaryRow++;
        $sheet->setCellValue('A' . $summaryRow, 'Total Appointments:');
        $sheet->setCellValue('B' . $summaryRow, $totalAppointments);
        $sheet->getStyle('A' . $summaryRow)->applyFromArray(['font' => ['bold' => true]]);
        
        // Approved appointments
        $summaryRow++;
        $sheet->setCellValue('A' . $summaryRow, 'Approved:');
        $sheet->setCellValue('B' . $summaryRow, $approvedCount);
        $sheet->getStyle('A' . $summaryRow)->applyFromArray(['font' => ['bold' => true]]);
        
        // Pending appointments
        $summaryRow++;
        $sheet->setCellValue('A' . $summaryRow, 'Pending:');
        $sheet->setCellValue('B' . $summaryRow, $pendingCount);
        $sheet->getStyle('A' . $summaryRow)->applyFromArray(['font' => ['bold' => true]]);
        
        // Canceled appointments
        $summaryRow++;
        $sheet->setCellValue('A' . $summaryRow, 'Canceled:');
        $sheet->setCellValue('B' . $summaryRow, $canceledCount);
        $sheet->getStyle('A' . $summaryRow)->applyFromArray(['font' => ['bold' => true]]);
        
        return [];
    }
} 