<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>PV - {{ $module->code }}</title>
    <style>
        body {
            font-family: 'brawler', serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0 0 10px 0;
        }
        .header p {
            margin: 3px 0;
            font-size: 10pt;
        }
        .info-section {
            margin: 20px 0;
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
        }
        .info-section table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-section td {
            padding: 5px;
            font-size: 10pt;
        }
        .info-section td:first-child {
            font-weight: bold;
            width: 150px;
        }
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 9pt;
        }
        .grades-table th {
            background: #333;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #333;
        }
        .grades-table td {
            padding: 6px 5px;
            border: 1px solid #222;
        }
        .grades-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .stats-section {
            margin: 20px 0;
            padding: 15px;
            background: #f0f0f0;
            border-left: 4px solid #333;
        }
        .stats-section h3 {
            margin: 0 0 15px 0;
            font-size: 12pt;
        }
        .stats-table {
            width: 100%;
            border-collapse: collapse;
        }
        .stats-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #222;
            background: white;
        }
        .stat-label {
            font-size: 9pt;
            color: #666;
            font-weight: bold;
        }
        .stat-value {
            font-size: 14pt;
            font-weight: bold;
            color: #333;
            margin-top: 3px;
        }
        .stat-value.success {
            color: #111;
        }
        .stat-value.danger {
            color: #111;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 9pt;
            color: #666;
            text-align: right;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
        }
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .logo {
            max-width: 300px;
            height: auto;
            margin-bottom: 40px ;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <img src="{{ storage_path('app/public/logos/logo_fac_fr.png') }}" alt="Logo Faculté" class="logo">

        <h1>PROCÈS-VERBAL DES NOTES</h1>
        <p><strong>Module:</strong> {{ $module->code }} - {{ $module->label }} ({{$module->semester}})</p>
        <p><strong>Filière:</strong> {{ $module->filiere->label_fr }}</p>
        <p><strong>Année Académique:</strong> {{ $academicYear->label }}</p>
        <p><strong>Session:</strong> 
            @if($examPeriod)
                {{ $examPeriod->season === 'autumn' ? 'Automne' : 'Printemps' }} - {{ ucfirst($session) === 'Normal' ? 'Normale' : 'Rattrapage' }}
            @else
                {{ ucfirst($session) === 'Normal' ? 'Normale' : 'Rattrapage' }}
            @endif
        </p>
    </div>
    <!-- Exam Information -->
    @if($exam)
    <div class="info-section">
        <table>
            <tr>
                <td>Date d'examen:</td>
                <td>{{ $exam->exam_date ? $exam->exam_date->format('d/m/Y') : '--' }}</td>
            </tr>
            <tr>
                <td>Horaire:</td>
                <td>
                    @if($exam->start_time && $exam->end_time)
                        {{ $exam->start_time->format('H:i') }} - {{ $exam->end_time->format('H:i') }}
                    @else
                        --:-- - --:--
                    @endif
                </td>
            </tr>
            <tr>
                <td>Lieu:</td>
                <td>{{ !empty($examLocations) ? implode(', ', $examLocations) : 'Non défini' }}</td>
            </tr>
        </table>
    </div>
    @endif
    {{-- @dd($exam) --}}

    <!-- Statistics -->
    <div class="stats-section">
        <h3>Statistiques</h3>
        <table class="stats-table">
            <tr>
                <td>
                    <div class="stat-label">Total Étudiants</div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                </td>
                <td>
                    <div class="stat-label">Notes Publiées</div>
                    <div class="stat-value">{{ $stats['published'] }}</div>
                </td>
                <td>
                    <div class="stat-label">Moyenne</div>
                    <div class="stat-value">{{ number_format($stats['avg_grade'], 2) }}</div>
                </td>
                <td>
                    <div class="stat-label">Validés</div>
                    <div class="stat-value success">{{ $stats['passed'] }}</div>
                </td>
                <td>
                    <div class="stat-label">Non Validés</div>
                    <div class="stat-value danger">{{ $stats['failed'] }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Grades Table -->
    <table class="grades-table">
        <thead>
            <tr>
                <th style="width: 8%;">N°Examen</th>
                <th style="width: 12%;">Apogee</th>
                <th style="width: 35%;">Nom et Prénom</th>
                <th style="width: 10%;" class="text-center">Note</th>
                <th style="width: 15%;" class="text-center">Résultat</th>
                <th style="width: 20%;" class="text-center">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grades as $index => $grade)
            <tr>
                <td class="text-center">{{ $grade->n_examen ?? '-' }}</td>
                <td>{{ $grade->apogee }}</td>
                <td>{{ $grade->nom }} {{ $grade->prenom }}</td>
                <td class="text-center">
                    @if($grade->grade !== null)
                        <strong>{{ number_format($grade->grade, 2) }}</strong>
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @if($grade->result)
                        @if($grade->result === 'validé')
                            <span class="badge badge-success">{{ ucfirst($grade->result) }}</span>
                        @elseif($grade->result === 'non validé')
                            <span class="badge badge-danger">{{ ucfirst($grade->result) }}</span>
                        @else
                            <span class="badge badge-warning">{{ ucfirst($grade->result) }}</span>
                        @endif
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @if($grade->is_published)
                        <span class="badge badge-success">Publiée</span>
                    @else
                        <span class="badge badge-warning">Non publiée</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>Document généré le {{ $generatedAt->format('d/m/Y à H:i') }}</p>
        <p>Page {PAGENO} sur {nbpg}</p>
    </div>
</body>
</html>
