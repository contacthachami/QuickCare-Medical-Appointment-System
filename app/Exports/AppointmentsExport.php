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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class AppointmentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize, WithProperties, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Auth::user()->patient->appointments()
            ->with(['doctor.user', 'schedule'])
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Numéro de rendez-vous',
            'Motif',
            'Date du rendez-vous',
            'Nom du médecin',
            'Heure de début',
            'Statut'
        ];
    }

    /**
     * @param mixed $appointment
     * @return array
     */
    public function map($appointment): array
    {
        // Récupérer l'heure de début en toute sécurité
        $startTime = $appointment->schedule ? $appointment->schedule->start : '';
        
        // Récupérer le nom du médecin en toute sécurité
        $doctorName = $appointment->doctor && $appointment->doctor->user ? $appointment->doctor->user->name : '';
        
        // Formater la date pour une meilleure lisibilité
        $formattedDate = Carbon::parse($appointment->appointment_date)->format('d/m/Y');
        
        // Traduire le statut en français
        $statusText = $appointment->status;
        if ($statusText === 'Completed') $statusText = 'Terminé';
        if ($statusText === 'Pending') $statusText = 'En attente';
        if ($statusText === 'Cancelled') $statusText = 'Annulé';
        
        return [
            $appointment->id,
            $appointment->reason,
            $formattedDate,
            $doctorName,
            $startTime,
            $statusText
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Mes Rendez-vous';
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
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
            'title'          => 'Liste des Rendez-vous',
            'description'    => 'Liste des rendez-vous médicaux exportée depuis QuickCare',
            'subject'        => 'Rendez-vous Médicaux',
            'company'        => 'QuickCare',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Style pour l'en-tête
        $sheet->getStyle('A1:F1')->applyFromArray([
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

        // Appliquer des bordures à toutes les cellules
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:F' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Centrer certaines colonnes
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E2:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F2:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Appliquer des couleurs différentes selon le statut
        for ($i = 2; $i <= $lastRow; $i++) {
            $status = $sheet->getCell('F' . $i)->getValue();
            
            if ($status == 'Terminé') {
                $sheet->getStyle('F' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'C6EFCE'], // Vert clair
                    ],
                    'font' => [
                        'color' => ['rgb' => '006100'], // Vert foncé
                    ],
                ]);
            } elseif ($status == 'En attente') {
                $sheet->getStyle('F' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFEB9C'], // Jaune clair
                    ],
                    'font' => [
                        'color' => ['rgb' => '9C5700'], // Orange foncé
                    ],
                ]);
            } elseif ($status == 'Annulé') {
                $sheet->getStyle('F' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFC7CE'], // Rouge clair
                    ],
                    'font' => [
                        'color' => ['rgb' => '9C0006'], // Rouge foncé
                    ],
                ]);
            }
        }

        // Appliquer un style alterné pour les lignes (zébré)
        for ($i = 2; $i <= $lastRow; $i++) {
            if ($i % 2 == 0) {
                $sheet->getStyle('A' . $i . ':F' . $i)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F9F9F9'],
                    ],
                ]);
            }
        }

        return [];
    }
}