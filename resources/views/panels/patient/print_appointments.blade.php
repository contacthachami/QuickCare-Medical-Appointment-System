<!DOCTYPE html>
<html>
<head>
    <title>QuickCare - My Appointments List</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }
        * {
            box-sizing: border-box;
            font-family: 'Poppins', Arial, sans-serif;
        }
        body {
            margin: 0;
            padding: 20px;
            background-color: #fff;
            color: #333;
            line-height: 1.6;
        }
        .report-container {
            max-width: 100%;
            margin: 0 auto;
        }
        .report-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #74C0FC;
            position: relative;
        }
        .report-header::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 1px;
            background-color: #e5e7eb;
        }
        .logo-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .logo {
            max-height: 70px;
            margin-right: 15px;
        }
        .report-title {
            font-size: 28px;
            font-weight: 600;
            color: #2563EB;
            margin: 5px 0;
            letter-spacing: -0.5px;
        }
        .report-subtitle {
            font-size: 16px;
            color: #4B5563;
            margin-bottom: 5px;
            font-weight: 500;
        }
        .report-date {
            font-size: 14px;
            color: #6B7280;
            font-style: italic;
        }
        .patient-info {
            background-color: #F3F4F6;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 25px;
            border-left: 4px solid #74C0FC;
        }
        .info-label {
            font-weight: 600;
            color: #1F2937;
            margin-right: 5px;
        }
        .info-value {
            color: #4B5563;
        }
        .table-container {
            overflow-x: auto;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            overflow: hidden;
        }
        thead {
            background-color: #F3F4F6;
        }
        th {
            color: #1F2937;
            font-weight: 600;
            text-align: left;
            padding: 14px 16px;
            border-bottom: 2px solid #D1D5DB;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        td {
            padding: 12px 16px;
            border-bottom: 1px solid #E5E7EB;
            color: #4B5563;
            font-size: 14px;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        tr:hover {
            background-color: #F3F4F6;
        }
        .status {
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
            text-align: center;
            min-width: 90px;
        }
        .status-pending {
            background-color: #FEF3C7;
            color: #92400E;
        }
        .status-cancelled, .status-expired {
            background-color: #FEE2E2;
            color: #B91C1C;
        }
        .status-approved {
            background-color: #D1FAE5;
            color: #065F46;
        }
        .report-footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .footer-left {
            font-size: 13px;
            color: #6B7280;
        }
        .footer-right {
            text-align: right;
            font-size: 13px;
            color: #6B7280;
        }
        .page-number {
            position: fixed;
            bottom: 15px;
            right: 15px;
            font-size: 11px;
            color: #9CA3AF;
            background-color: #F3F4F6;
            padding: 3px 8px;
            border-radius: 4px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(229, 231, 235, 0.3);
            pointer-events: none;
            z-index: -1;
            font-weight: 700;
            white-space: nowrap;
        }
        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none;
            }
            .print-button {
                display: none;
            }
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 15px;
            background-color: #3B82F6;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 999;
        }
        .print-button:hover {
            background-color: #2563EB;
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print();">Print</button>
    
    <div class="watermark">QUICKCARE</div>
    <div class="report-container">
        <div class="report-header">
            <div class="logo-container">
                <img src="{{ asset('img/app-logo.png') }}" alt="QuickCare Logo" class="logo" onerror="this.style.display='none'">
            </div>
            <h1 class="report-title">Mes Rendez-vous</h1>
            <p class="report-subtitle">QuickCare Healthcare System</p>
            <p class="report-date">Généré le {{ $generatedDate }}</p>
        </div>
        
        <div class="patient-info">
            <span class="info-label">Patient:</span>
            <span class="info-value">{{ $patientName }}</span>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Numéro de rendez-vous</th>
                        <th>Motif</th>
                        <th>Date du rendez-vous</th>
                        <th>Nom du médecin</th>
                        <th>Heure de début</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ $appointment->id }}</td>
                        <td>{{ $appointment->reason }}</td>
                        <td>{{ $appointment->appointment_date }}</td>
                        <td>{{ $appointment->doctor->user->name }}</td>
                        <td>{{ $appointment->schedule->start }}</td>
                        <td>
                            @if($appointment->status == 'Pending')
                            <span class="status status-pending">{{ $appointment->status }}</span>
                            @elseif($appointment->status == 'Expired' || $appointment->status == 'Cancelled')
                            <span class="status status-expired">{{ $appointment->status }}</span>
                            @elseif($appointment->status == 'Approved')
                            <span class="status status-approved">{{ $appointment->status }}</span>
                            @else
                            {{ $appointment->status }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="report-footer">
            <div class="footer-left">
                <p>Ce document est un rapport officiel de QuickCare Healthcare System.</p>
                <p>Total des rendez-vous: {{ count($appointments) }}</p>
            </div>
            <div class="footer-right">
                <p>QuickCare Healthcare © {{ date('Y') }}</p>
            </div>
        </div>
        
        <div class="page-number">Page 1</div>
    </div>
    
    <script>
        // Automatically print when loaded
        window.onload = function() {
            // Add a short delay to ensure everything is loaded
            setTimeout(function() {
                // The print dialog will open automatically
                // window.print();
            }, 1000);
        };
    </script>
</body>
</html> 