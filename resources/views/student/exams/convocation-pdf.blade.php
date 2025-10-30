<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Convocation d'Examen</title>
  <style>
    body {
      font-family: 'roboto', sans-serif;
      font-size: 11pt;
      color: #1f2937;
      margin: 0;
      padding: 0;
      line-height: 1.35;
    }

    .page {
      padding: 18px;
    }

    /* Header */
    .header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 2px solid #2563eb;
  padding-bottom: 6px;
  margin-bottom: 10px;
  gap: 10px; /* Add this */
}

    .header .left {
      flex: 1;
    }

    .header .center {
      flex: 2;
      text-align: center;
    }

    .header .right {
      flex: 1;
      text-align: right;
    }

    .header img {
      vertical-align: middle;
    }

    .avatar {
      width: 65px;
      height: 65px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #e5e7eb;
    }

    /* Title */
    .document-title {
      font-size: 16pt;
      font-weight: 700;
      text-align: center;
      color: #111827;
      margin: 10px 0 4px;
      letter-spacing: 0.4px;
    }

    .document-subtitle {
      font-size: 9pt;
      color: #6b7280;
      text-align: center;
      margin-bottom: 10px;
    }

    /* Student Info */
    .student-info {
      background: #f9fafb;
      border-left: 3px solid #2563eb;
      padding: 8px 10px;
      margin-bottom: 12px;
      border-radius: 3px;
    }

    .info-row {
      display: flex;
      font-size: 8.5pt;
      margin-bottom: 3px;
    }

    .info-label {
      width: 120px;
      color: #374151;
      font-weight: 600;
    }

    .info-value {
      color: #111827;
      font-weight: 500;
    }

    /* Table */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 8px;
      margin-bottom: 12px;
    }

    th, td {
      border: 1px solid #e5e7eb;
      padding: 5px 4px;
      font-size: 8pt;
    }

    thead th {
      background: #f3f4f6;
      color: #374151;
      text-align: left;
      font-weight: 600;
    }

    tbody tr:nth-child(even) {
      background: #f9fafb;
    }

    td {
      vertical-align: middle;
    }

    .module-name {
      font-weight: 600;
      color: #111827;
    }

    .session-badge {
      background: #fee2e2;
      color: #991b1b;
      font-size: 6pt;
      font-weight: bold;
      padding: 1px 4px;
      border-radius: 2px;
      margin-right: 4px;
    }

    /* Two-column notes */
    .row {
      display: flex;
      gap: 8px;
      margin-top: 10px;
    }

    .col {
      flex: 1;
      font-size: 7.5pt;
    }

    .notice, .instructions {
      border-left: 3px solid;
      padding: 7px 8px;
      border-radius: 2px;
    }

    .notice {
      background: #fef3c7;
      border-color: #f59e0b;
    }

    .instructions {
      background: #fee2e2;
      border-color: #ef4444;
    }

    .block-title {
      font-weight: 700;
      margin-bottom: 3px;
    }

    ul {
      margin: 3px 0;
      padding-left: 15px;
    }

    li {
      margin-bottom: 2px;
    }

    /* Footer */
    .footer {
      text-align: center;
      font-size: 7pt;
      color: #6b7280;
      border-top: 1px solid #e5e7eb;
      padding-top: 6px;
      margin-top: 12px;
    }

    .reference {
      font-family: 'Courier New', monospace;
      font-size: 6pt;
      color: #9ca3af;
      margin-top: 3px;
    }
  </style>
</head>
<body>
  <div class="page">

    <!-- Header -->
   <!-- Header -->
<!-- Header -->
<table style="width: 100%; border-collapse: collapse; border-bottom: 2px solid #2563eb; padding-bottom: 6px; margin-bottom: 10px;">
    <tr>
      <td style="width: 33%; vertical-align: middle; border: none;">
        @if(!empty($logoBase64))
          <img src="{{ $logoBase64 }}" alt="Logo" style="width:180px;">
        @endif
      </td>
      <td style="width: 34%; text-align: center; vertical-align: middle; border: none;">
        <strong style="font-size:11pt;">Faculté des Sciences Appliquées</strong><br>
        <span style="font-size:8pt; color:#6b7280;">Université Ibn Zohr</span>
      </td>
      <td style="width: 33%; text-align: right; vertical-align: middle; border: none;">
        @if(!empty($qrCodeBase64))
          <img src="{{ $qrCodeBase64 }}" alt="QR" style="width:70px; height:70px;">
        @endif
      </td>
    </tr>
  </table>

    <!-- Title -->
    <div class="document-title">CONVOCATION D'EXAMEN</div>
    <div class="document-subtitle">
      {{ $enrollment->academic_year }}/{{ $enrollment->academic_year + 1 }} —
      {{ strtoupper($session) }} / {{ strtoupper($season) }}
    </div>

    <!-- Student Info -->
   <!-- Student Info -->
<!-- Student Info -->
<table style="width: 100%; background: #f9fafb; border-left: 3px solid #2563eb; padding: 8px 10px; margin-bottom: 12px; border-radius: 3px; border-collapse: collapse;">
    <tr>
      <td style="vertical-align: top; padding-right: 10px; border: none;">
        <div class="info-row"><span class="info-label">ÉTUDIANT :</span> <span class="info-value">{{ strtoupper($student->nom) }} {{ strtoupper($student->prenom) }}</span></div>
        <div class="info-row"><span class="info-label">CNE / APOGÉE :</span> <span class="info-value">{{ $student->cne }} / {{ $student->apogee ?? 'N/A' }}</span></div>
        <div class="info-row"><span class="info-label">CIN :</span> <span class="info-value">{{ $student->cin ?? 'N/A' }}</span></div>
        <div class="info-row"><span class="info-label">FILIÈRE :</span> <span class="info-value">{{ $enrollment->filiere->label_fr ?? 'N/A' }}</span></div>
      </td>
      <td style="width: 100px; text-align: right; vertical-align: top;border: none;padding:0;">
        @if(!empty($photoBase64))
          <img src="{{ $photoBase64 }}" alt="Photo" class="avatar">
        @endif
      </td>
    </tr>
  </table>

    <!-- Exams Table -->
    <table>
      <thead>
        <tr>
            <th style="width:14%;">Semestre</th>
          <th style="width:1%;">N° Examen</th>
          <th style="width:38%;">Module</th>
          <th style="width:11%;">Date</th>
          <th style="width:9%;">Heure</th>
          <th style="width:14%;">Salle</th>
        </tr>
    </thead>
    <tbody>
        @foreach($exams as $i => $exam)
        <tr>
            <td>{{ $exam['semester'] }}</td>
          <td align="center"><strong>{{ $i + 1 }}</strong></td>
          <td>
            @if(($exam['session'] ?? '') === 'Rattrapage')
              <span class="session-badge">R</span>
            @endif
            <span class="module-name">{{ $exam['module_label_clean'] ?? $exam['module_label'] ?? 'N/A' }}</span>
          </td>
          <td><strong>{{ isset($exam['date']) ? $exam['date']->format('d/m/Y') : 'N/A' }}</strong></td>
          <td>{{ $exam['time'] ?? 'N/A' }}</td>
           <td>{{ $exam['room'] ?? 'N/A' }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Notices -->
    <div class="row">
      <div class="col">
        <div class="notice">
          <div class="block-title">DOCUMENTS REQUIS</div>
          <ul>
            <li>Carte d'identité nationale (CIN)</li>
            <li>Carte d'étudiant ou attestation</li>
            <li>Cette convocation imprimée</li>
          </ul>
        </div>
      </div>
      <div class="col">
        <div class="instructions">
          <div class="block-title">INSTRUCTIONS</div>
          <ul>
            <li>Arriver 15 minutes avant le début</li>
            <li>Téléphones strictement interdits</li>
            <li>Toute fraude sera sanctionnée</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="footer">
      Document généré le {{ $generatedAt->format('d/m/Y') }} à {{ $generatedAt->format('H:i') }} —
      {{ count($exams) }} examen(s)
      <div class="reference">
        REF: {{ strtoupper(substr(md5($student->cne . count($exams) . $generatedAt), 0, 16)) }}
      </div>
    </div>
  </div>
</body>
</html>
