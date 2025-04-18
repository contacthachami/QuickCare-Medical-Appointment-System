<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Travel Records Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 10px;
        }
        h1 {
            font-size: 22px;
            margin: 0 0 10px 0;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
            margin: 0;
        }
        .doctor-info {
            margin-bottom: 20px;
        }
        .summary {
            background-color: #f9fafb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .summary-item {
            margin-bottom: 10px;
        }
        .summary-label {
            font-weight: bold;
            width: 180px;
            display: inline-block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f3f4f6;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border-bottom: 2px solid #ddd;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">QuickCare</div>
        <h1>Doctor Travel Records Report</h1>
        <p class="subtitle">Generated on {{ $exportDate }}</p>
    </div>
    
    <div class="doctor-info">
        <p><strong>Doctor:</strong> {{ $doctor->user->name }}</p>
        <p><strong>Specialty:</strong> {{ $doctor->speciality->name }}</p>
    </div>
    
    <div class="summary">
        <div class="summary-item">
            <span class="summary-label">Total Travel Records:</span> {{ count($appointments) }}
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Travel Time:</span> {{ floor($totalTime / 60) }}h {{ $totalTime % 60 }}m ({{ $totalTime }} minutes)
        </div>
        <div class="summary-item">
            <span class="summary-label">Average Travel Time:</span> {{ $avgTime }} minutes per appointment
        </div>
    </div>
    
    <h2>Travel Records</h2>
    
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Patient</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Travel Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                    <td>{{ $appointment->patient->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->check_in_time)->format('h:i A') }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->check_out_time)->format('h:i A') }}</td>
                    <td>{{ $appointment->travel_time_minutes }} minutes</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>© {{ date('Y') }} QuickCare - Doctor Travel Management System</p>
    </div>
</body>
</html> 