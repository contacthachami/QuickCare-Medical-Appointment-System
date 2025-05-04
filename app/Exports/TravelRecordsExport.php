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
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;

class TravelRecordsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, WithProperties, WithColumnFormatting, WithCustomStartCell
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Auth::user()->doctor->appointments()
            ->whereNotNull('check_in_time')
            ->with(['patient.user.address'])
            ->orderBy('appointment_date', 'desc')
            ->get();
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return 'A1';
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Date',
            'Patient Name',
            'Appointment Time',
            'Check-In Time',
            'Check-Out Time',
            'Travel Time (minutes)',
            'Location'
        ];
    }

    /**
     * @param mixed $appointment
     * @return array
     */
    public function map($appointment): array
    {
        // Format the appointment date for display
        $appointmentDate = Carbon::parse($appointment->appointment_date)->format('Y-m-d');
        
        // Get patient name
        $patientName = $appointment->patient && $appointment->patient->user ? 
            $appointment->patient->user->name : 'Unknown Patient';
        
        // Get reason for appointment
        $reason = $appointment->reason ?? 'Unknown Service';
        
        // Format patient name with reason
        $patientAndReason = $patientName . ' - ' . $reason;
        
        // Format the appointment time
        $appointmentTime = Carbon::parse($appointment->appointment_date)->format('h:i A');
        
        // Format check-in time
        $checkInTime = $appointment->check_in_time ? 
            Carbon::parse($appointment->check_in_time)->format('h:i A') : 'Not recorded';
        
        // Format check-out time
        $checkOutTime = $appointment->check_out_time ? 
            Carbon::parse($appointment->check_out_time)->format('h:i A') : 'Not recorded';
        
        // Get travel time
        $travelTime = $appointment->travel_time_minutes ?? 'N/A';
        
        // Get patient location
        $location = 'N/A';
        if ($appointment->patient && $appointment->patient->user && $appointment->patient->user->address) {
            $address = $appointment->patient->user->address;
            $location = $address->ville . ', ' . $address->rue;
        }
            
        return [
            $appointmentDate,
            $patientAndReason,
            $appointmentTime,
            $checkInTime,
            $checkOutTime,
            $travelTime,
            $location
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Travel Records';
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'C' => NumberFormat::FORMAT_DATE_TIME3,
            'D' => NumberFormat::FORMAT_DATE_TIME3,
            'E' => NumberFormat::FORMAT_DATE_TIME3,
            'F' => NumberFormat::FORMAT_NUMBER,
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
            'title'          => 'Travel Records',
            'description'    => 'Doctor travel records exported from QuickCare',
            'subject'        => 'Medical Travel Records',
            'company'        => 'QuickCare',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        // Add a title at the top
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'QuickCare - Travel Records');
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
        $sheet->mergeCells('A2:G2');
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
        $sheet->mergeCells('A3:G3');
        $doctorName = Auth::user()->name;
        $sheet->setCellValue('A3', 'Doctor: ' . $doctorName);
        $sheet->getStyle('A3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
        ]);
        
        // Add empty row before headers
        $sheet->mergeCells('A4:G4');
        
        // Style for headers - now at row 5
        $sheet->getStyle('A5:G5')->applyFromArray([
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
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Apply borders to all cells in the data
        $dataRows = 'A5:G' . $lastRow;
        $sheet->getStyle($dataRows)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Center specific columns
        $sheet->getStyle('A6:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C6:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Highlight travel time based on duration
        for ($i = 6; $i <= $lastRow; $i++) {
            $travelTime = $sheet->getCell('F' . $i)->getValue();
            
            if (is_numeric($travelTime)) {
                if ($travelTime <= 15) {
                    $sheet->getStyle('F' . $i)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'C6EFCE'], // Light green
                        ],
                        'font' => [
                            'color' => ['rgb' => '006100'], // Dark green
                        ],
                    ]);
                } elseif ($travelTime <= 30) {
                    $sheet->getStyle('F' . $i)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FFEB9C'], // Light yellow
                        ],
                        'font' => [
                            'color' => ['rgb' => '9C5700'], // Dark orange
                        ],
                    ]);
                } else {
                    $sheet->getStyle('F' . $i)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FFC7CE'], // Light red
                        ],
                        'font' => [
                            'color' => ['rgb' => '9C0006'], // Dark red
                        ],
                    ]);
                }
            }
        }

        // Apply alternating row colors (zebra striping)
        for ($i = 6; $i <= $lastRow; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':G' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F5F5F5'],
                    ],
                ]);
            }
        }

        // Add summary section
        $summaryStartRow = $lastRow + 2;
        
        $sheet->setCellValue('A' . $summaryStartRow, 'Travel Summary');
        $sheet->getStyle('A' . $summaryStartRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);
        
        // Count travel records
        $totalRecords = $lastRow - 5;
        $sheet->setCellValue('A' . ($summaryStartRow + 1), 'Total Records:');
        $sheet->setCellValue('B' . ($summaryStartRow + 1), $totalRecords);
        
        // Calculate completed trips
        $completedTrips = 0;
        $totalTravelTime = 0;
        
        for ($i = 6; $i <= $lastRow; $i++) {
            $travelTime = $sheet->getCell('F' . $i)->getValue();
            if (is_numeric($travelTime)) {
                $completedTrips++;
                $totalTravelTime += $travelTime;
            }
        }
        
        $sheet->setCellValue('A' . ($summaryStartRow + 2), 'Completed Trips:');
        $sheet->setCellValue('B' . ($summaryStartRow + 2), $completedTrips);
        
        $sheet->setCellValue('A' . ($summaryStartRow + 3), 'Total Travel Time:');
        $sheet->setCellValue('B' . ($summaryStartRow + 3), $totalTravelTime . ' minutes');
        
        if ($completedTrips > 0) {
            $averageTime = round($totalTravelTime / $completedTrips, 1);
            $sheet->setCellValue('A' . ($summaryStartRow + 4), 'Average Travel Time:');
            $sheet->setCellValue('B' . ($summaryStartRow + 4), $averageTime . ' minutes per trip');
        }
        
        // Style summary rows
        for ($i = $summaryStartRow; $i <= $summaryStartRow + 4; $i++) {
            $sheet->getStyle('A' . $i)->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
            ]);
        }

        return [];
    }
} 