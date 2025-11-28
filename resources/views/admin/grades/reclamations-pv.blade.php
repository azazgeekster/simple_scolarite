<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PV Réclamations</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'brawler', Arial, sans-serif;
            font-size: 9px;
            line-height: 1.4;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            font-size: 16px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 12px;
            color: #555;
            margin-bottom: 3px;
        }

        .info-section {
            margin-bottom: 15px;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            color: #333;
        }

        ..stats {
    width: 100%;
    margin: 15px 0;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 4px;
}

.stats td {
    border: none !important;
}

.stat-item {
    text-align: center;
    padding: 0 12px;
}

.stat-value,
.stat-value-resolved,
.stat-value-rejected,
.stat-value-pending,
.stat-value-changed {
    font-size: 16px;
    font-weight: bold;
    margin-right: 5px;
}

.stat-value {
    color: #000;
}

.stat-value-resolved {
    color: #000;
}

.stat-value-rejected {
    color: #000;
}

.stat-value-pending {
    color: #000;
}

.stat-value-changed {
    color: #000;
}

.stat-label {
    font-size: 9px;
    color: #000;
    font-weight: normal;
}

.stat-divider {
    color: #ccc;
    padding: 0 5px;
    text-align: center;
    font-size: 12px;
}

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead {
            background: #2c5282;
            color: white;
        }

        th {
            padding: 6px 4px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            border: 1px solid #000;
        }

        td {
            padding: 5px 4px;
            border: 1px solid #111;
            font-size: 8px;
        }

        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }



        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            white-space: nowrap;
        }

        .status-resolved {
            background: #c6f6d5;
            color: #22543d;
        }

        .status-rejected {
            background: #fed7d7;
            color: #742a2a;
        }

        .status-pending {
            background: #fef5e7;
            color: #744210;
        }

        .status-review {
            background: #bee3f8;
            color: #2c5282;
        }

        .grade-change {
            font-weight: bold;
            color: #d69e2e;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 8px;
            color: #666;
        }

        .signatures {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .signature-box {
            text-align: center;
            width: 30%;
        }

        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #000;
            padding-top: 5px;
            font-size: 8px;
        }
        .logo {
            max-width: 300px;
            height: auto;
            margin-bottom: 40
            /* margin: 0 auto; */
        }
    </style>
</head>
<body>
    <div class="header">

        <img src="{{ storage_path('app/public/logos/logo_fac_fr.png') }}" alt="Logo Faculté" class="logo">

        <h1>Procès-Verbal des Réclamations</h1>
        <h2>Année Universitaire {{ $academicYear?->label ?? $session }}</h2>
        <p style="font-size: 10px; margin-top: 5px;">
            Session: <strong>{{ ucfirst($session) }}</strong>
            @if($filiere)
                | Filière: <strong>{{ $filiere->label_fr }}</strong>
            @endif
            @if($semester)
                | Semestre: <strong>{{ $semester }}</strong>
            @endif
            @if($module)
                | Module: <strong>{{ $module->code }} - {{ $module->label }}</strong>
            @endif
        </p>
    </div>

    <table class="stats" cellpadding="0" cellspacing="0">
        <tr>
            <td class="stat-item">
                <span class="stat-value">{{ $stats['total'] }}</span>
                <span class="stat-label">Total</span>
            </td>
            <td class="stat-divider"> </td>
            <td class="stat-item">
                <span class="stat-value-resolved">{{ $stats['resolved'] }}</span>
                <span class="stat-label">Résolues</span>
            </td>
            <td class="stat-divider"> </td>
            <td class="stat-item">
                <span class="stat-value-rejected">{{ $stats['rejected'] }}</span>
                <span class="stat-label">Rejetées</span>
            </td>
            <td class="stat-divider">|</td>
            <td class="stat-item">
                <span class="stat-value-pending">{{ $stats['pending'] }}</span>
                <span class="stat-label">En attente</span>
            </td>
            <td class="stat-divider">|</td>
            <td class="stat-item">
                <span class="stat-value-changed">{{ $stats['grade_changed'] }}</span>
                <span class="stat-label">Modifiées</span>
            </td>
        </tr>
    </table>
     <table>
        <thead>
            <tr>
                <th style="width: 6%;">Réf</th>
                <th style="width: 8%;">CNE</th>
                <th style="width: 12%;">Nom</th>
                <th style="width: 12%;">Prénom</th>
                <th style="width: 15%;">Module</th>
                <th style="width: 10%;">Type</th>
                <th style="width: 5%;">N. Orig</th>
                <th style="width: 5%;">N. Corr</th>
                <th style="width: 8%;">Statut</th>
                <th style="width: 7%;">Date</th>
                <th style="width: 12%;">Corrigé Par</th>
                <th style="width: 12%;">Corrigé le</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reclamations as $reclamation)
                @php
                    $student = $reclamation->moduleGrade?->moduleEnrollment?->student;
                    $mod = $reclamation->moduleGrade?->moduleEnrollment?->module;
                @endphp
                <tr>
                    <td>{{ $reclamation->reference }}</td>
                    <td>{{ $student?->cne }}</td>
                    <td>{{ $student?->nom }}</td>
                    <td>{{ $student?->prenom }}</td>
                    <td>{{ $mod?->code }}</td>
                    <td>{{ $reclamation->getReclamationTypeLabel() }}</td>
                    <td style="text-align: center;">{{ $reclamation->original_grade ?? '-' }}</td>
                    <td style="text-align: center;" class="{{ $reclamation->hasGradeChange() ? 'grade-change' : '' }}">
                        {{ $reclamation->revised_grade ?? '-' }}
                    </td>
                    <td>
                        <span class="status-badge status-{{ strtolower($reclamation->status) }}">
                            {{ $reclamation->getStatusLabel() }}
                        </span>
                    </td>
                     <td>{{ $reclamation->created_at?->format('d/m/Y') }}</td>
                    <td>{{ $reclamation->corrector?->name ?? '-' }}</td>
                    <td>{{ $reclamation->corrected_at ?? '-' }}</td>

                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align: center; padding: 20px;">Aucune réclamation trouvée</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signatures">
        <div class="signature-box">
            <div class="signature-line">
                Le Responsable Pédagogique
            </div>
        </div>
        <div class="signature-box">
            <div class="signature-line">
                Le Chef de Département
            </div>
        </div>
        <div class="signature-box">
            <div class="signature-line">
                Le Doyen / Directeur
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Document généré le {{ $generatedAt->format('d/m/Y à H:i') }}</p>
        <p style="margin-top: 5px;">Ce document est un procès-verbal officiel des réclamations traitées</p>
    </div>
</body>
</html>
