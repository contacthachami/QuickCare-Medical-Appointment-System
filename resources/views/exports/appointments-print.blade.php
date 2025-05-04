<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Appointments Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0891b2;
        }
        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .logo {
            width: 60px;
            height: 60px;
            margin-bottom: 5px;
            object-fit: contain;
        }
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #0891b2;
            margin: 0;
        }
        .ma-text {
            font-size: 20px;
            font-weight: bold;
            color: #0ea5e9;
            margin: 0;
        }
        .report-title {
            text-align: right;
        }
        h1 {
            font-size: 22px;
            margin: 0 0 5px 0;
            color: #0f172a;
        }
        .subtitle {
            font-size: 14px;
            color: #64748b;
            margin: 0;
        }
        .doctor-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8fafc;
            border-radius: 5px;
        }
        .summary {
            background-color: #f0f9ff;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            border-left: 5px solid #0ea5e9;
        }
        .summary-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .summary-item {
            flex: 1;
            min-width: 200px;
            margin-bottom: 10px;
        }
        .summary-label {
            font-weight: bold;
            color: #0f172a;
            margin-right: 5px;
        }
        .summary-value {
            color: #334155;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        th {
            background-color: #0891b2;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .status-approved {
            background-color: #dcfce7;
            color: #166534;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 500;
            display: inline-block;
        }
        .status-pending {
            background-color: #fef9c3;
            color: #854d0e;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 500;
            display: inline-block;
        }
        .status-canceled {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 500;
            display: inline-block;
        }
        .travel-completed {
            color: #166534;
            font-weight: 500;
        }
        .travel-progress {
            color: #1e40af;
            font-weight: 500;
        }
        .travel-not-started {
            color: #854d0e;
            font-weight: 500;
        }
        .footer {
            margin-top: 150px;
            font-size: 12px;
            color: #64748b;
        }
        .signatures {
            margin-top: 60px;
            width: 100%;
            position: relative;
        }
        .signature-box-left {
            position: absolute;
            left: 0;
            width: 45%;
        }
        .signature-box-right {
            position: absolute;
            right: 0;
            width: 45%;
        }
        .signature-title {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .signature-line {
            border-top: 1px solid #94a3b8;
            width: 100%;
            margin-bottom: 10px;
        }
        .signature-name {
            font-size: 18px;
            color: #0891b2;
            font-weight: bold;
            margin-top: 8px;
        }
        .logo-icon {
            font-size: 30px;
            font-weight: bold;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div></div> <!-- Empty div for spacing -->
        <div class="logo-container">
            <img src="{{ $logoPath }}" alt="QuickCare Logo" class="logo">
            <div class="logo-text">QuickCare</div>
            <div class="ma-text">MA</div>
        </div>
        <div class="report-title">
            <h1>My Appointments</h1>
            <p class="subtitle">Generated on {{ date('F d, Y h:i A') }}</p>
        </div>
    </div>
    
    <div class="doctor-info">
        <p><strong>Doctor:</strong> {{ Auth::user()->name }}</p>
        <p><strong>Specialty:</strong> {{ Auth::user()->doctor->speciality->name }}</p>
    </div>
    
    <div class="summary">
        <div class="summary-row">
            <div class="summary-item">
                <span class="summary-label">Total Appointments:</span>
                <span class="summary-value">{{ $appointments->count() }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Approved:</span>
                <span class="summary-value">{{ $appointments->where('status', 'Approved')->count() }}</span>
            </div>
        </div>
        <div class="summary-row">
            <div class="summary-item">
                <span class="summary-label">Pending:</span>
                <span class="summary-value">{{ $appointments->where('status', 'Pending')->count() }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Canceled:</span>
                <span class="summary-value">{{ $appointments->where('status', 'Canceled')->count() }}</span>
            </div>
        </div>
        <div class="summary-row">
            <div class="summary-item">
                <span class="summary-label">Total Travel Time:</span>
                <span class="summary-value">
                    @php
                        $totalTime = $appointments->sum('travel_time_minutes');
                        echo floor($totalTime / 60) . 'h ' . ($totalTime % 60) . 'm (' . $totalTime . ' minutes)';
                    @endphp
                </span>
            </div>
        </div>
    </div>
    
    <h2>Appointment Details</h2>
    
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Patient</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Travel</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->id }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}<br>
                        <small>{{ $appointment->schedule ? $appointment->schedule->start : \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</small>
                    </td>
                    <td>
                        {{ $appointment->patient->user->name }}<br>
                        <small>{{ $appointment->patient->user->email }}</small>
                    </td>
                    <td>{{ $appointment->reason }}</td>
                    <td>
                        @if($appointment->status == 'Approved')
                            <span class="status-approved">Approved</span>
                        @elseif($appointment->status == 'Pending')
                            <span class="status-pending">Pending</span>
                        @elseif($appointment->status == 'Canceled' || $appointment->status == 'Expired')
                            <span class="status-canceled">{{ $appointment->status }}</span>
                        @else
                            {{ $appointment->status }}
                        @endif
                    </td>
                    <td>
                        @if(!$appointment->check_in_time)
                            <span class="travel-not-started">Not started</span>
                        @elseif($appointment->check_in_time && !$appointment->check_out_time)
                            <span class="travel-progress">In progress</span>
                        @elseif($appointment->check_in_time && $appointment->check_out_time)
                            <span class="travel-completed">{{ $appointment->travel_time_minutes }} minutes</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="signatures">
        <div class="signature-box-left">
            <div class="signature-title">QUICKCARE SIGNATURE</div>
            <div class="signature-line"></div>
            <div class="signature-name">QuickCare</div>
        </div>
        
        <div class="signature-box-right">
            <div class="signature-title">DOCTOR SIGNATURE</div>
            <div class="signature-line"></div>
        </div>
    </div>
    
    <div class="footer">
        <p>This document is generated by QuickCare Medical Appointments System. {{ date('Y') }} QuickCare MA. All rights reserved.</p>
        <p>For any inquiries, please contact support@quickcare.com</p>
    </div>
</body>
</html> 