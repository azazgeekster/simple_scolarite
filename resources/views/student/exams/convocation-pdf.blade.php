<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Convocation d'Examen</title>
  <style>
    /* CSS global retained for context */
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
      position: relative;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .content-wrapper {
      flex: 1;
    }

    .bottom-section {
      margin-top: auto;
      padding-top: 20px;
    }

    /* Header (using table structure now) */
    .header {
        display: none; /* Hide old flex header */
    }

    /* Title */
    .document-title {
      font-size: 16pt;
      font-weight: 800;
      text-align: center;
      color: #111827;
      margin: 10px 0 4px;
      letter-spacing: 0.4px;
    }

    .document-subtitle {
      font-size:11pt;
      color: #111;
      text-align: center;
      margin-bottom: 10px;
    }

    /* Student Info (using table structure now) */
    .student-info {
      background: #f9fafb;
      border-left: 3px solid #2563eb;
      padding: 8px 10px;
      margin-bottom: 12px;
      border-radius: 3px;
    }

    .info-row {
      display: flex;
      font-size: 11pt;
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
      border: 1px solid #111;
      padding: 5px 4px;
      font-size: 8pt;
    }

    thead th {
      background: #111;
      color: #fff;
      text-align: left;
      font-weight: bold;
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
      background: #e0f2f1; /* Light cyan */
      color: #0d9488; /* Dark teal */
      font-size: 6pt;
      font-weight: bold;
      padding: 1px 4px;
      border-radius: 2px;
      margin-right: 4px;
    }

    /* --- Two-column Notices (New Styles) --- */
    /* Use a table structure for reliable two-column layout in PDF/print */
    .notice-row-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 8px 0; /* Simulates the gap */
      margin-top: 10px;
    }

    .notice-row-table td {
        border: none;
        padding: 0;
        vertical-align: top;
        width: 50%;
    }

    .notice-box {
      border-left: 3px solid #2563eb; /* Primary Blue for structure */
      padding: 7px 8px;
      border-radius: 2px;
      min-height: 80px; /* Ensure boxes are noticeable */
    }

    /* Style for the first column box (Documents) */
    .notice-box.documents {
      background: #f0f4f7; /* Very Light Blue/Grey */
      border-color: #2563eb; /* Stronger accent blue */
    }

    /* Style for the second column box (Instructions) */
    .notice-box.instructions-new {
      background: #f7f7f7; /* Slightly different very Light Grey */
      border-color: #111827; /* Darker accent (almost black) */
    }

    .block-title-new {
      font-weight: bold;
      margin-bottom: 4px;
      color: #2563eb; /* Use primary color for titles */
      font-size: 8pt;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .notice-box ul {
      margin: 3px 0;
      padding-left: 15px;
      list-style-type: square;
      font-size: 7.5pt;
    }

    .notice-box li {
      margin-bottom: 2px;
      line-height: 1.2;
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
    <div class="content-wrapper">
      <table style="width: 100%; border-collapse: collapse; border-bottom: 2px solid #2563eb; padding-bottom: 6px; margin-bottom: 10px;">
          <tr>
            <td style="width: 50%; vertical-align: middle; border: none; padding: 0;">
              @if(!empty($logoBase64))
                <img src="{{ $logoBase64 }}" alt="Logo" style="width:200px;">
              @endif
            </td>
            <td style="width: 50%; text-align: right; vertical-align: middle; border: none; padding: 0;">
              @if(!empty($qrCodeBase64))
                <img src="{{ $qrCodeBase64 }}" alt="QR" style="width:70px; height:70px;">
              @endif
            </td>
          </tr>
      </table>

      <div class="document-title">CONVOCATION D'EXAMEN</div>
      <div class="document-subtitle">
        {{ $enrollment->academic_year }}/{{ $enrollment->academic_year + 1 }} —
        {{ strtoupper($session) }} / {{ strtoupper($season) }}
      </div>

      <table style="width: 100%; background: #f9fafb; border-left: 3px solid #2563eb; padding: 8px 10px; margin-bottom: 12px; border-radius: 3px; border-collapse: collapse;">
          <tr>
            <td style="vertical-align: top; padding-right: 10px; border: none; width: auto;">
              <div class="info-row"><span class="info-label">ÉTUDIANT :</span> <span class="info-value">{{ strtoupper($student->nom) }} {{ strtoupper($student->prenom) }}</span></div>
              <div class="info-row"><span class="info-label">CNE / APOGÉE :</span> <span class="info-value">{{ $student->cne }} / {{ $student->apogee ?? 'N/A' }}</span></div>
              <div class="info-row"><span class="info-label">CIN :</span> <span class="info-value">{{ $student->cin ?? 'N/A' }}</span></div>
              <div class="info-row"><span class="info-label">FILIÈRE :</span> <span class="info-value">{{ $enrollment->filiere->label_fr ?? 'N/A' }}</span></div>
            </td>
            <td style="width: 90px; text-align: right; vertical-align: top; border: none; padding:0;">
              @if(!empty($photoBase64))
                <img src="{{ $photoBase64 }}" alt="Photo" class="avatar" style="height: 100%; max-height: 80px; width: auto;">
              @endif
            </td>
          </tr>
      </table>

      <table style="border: 1px solid black">
        <thead>
          <tr>
              <th style="width:8%;">Sem.</th>
            <th style="width:1%;">N° Examen</th>
            <th style="width:43%;">Module</th>
            <th style="width:11%;">Date</th>
            <th style="width:10%;">Heure</th>
            <th style="width:10%;">Salle</th>
          </tr>
      </thead>
      <tbody>
          @foreach($exams as $i => $exam)
          <tr>
              <td>{{ $exam['semester'] }}</td>
            <td>{{ $exam['exam_number'] }}</strong></td>
            <td>
              @if(($exam['session'] ?? '') === 'Rattrapage')
                <span class="session-badge">R</span>
              @endif
              <span class="module-name">{{ $exam['module_label_clean'] ?? $exam['module_label'] ?? 'N/A' }}</span>
            </td>
            <td>{{ isset($exam['date']) ? $exam['date']->format('d/m/Y') : 'N/A' }}</td>
            <td>{{ $exam['stime'] .' - '.$exam['etime'] ?? 'N/A' }}</td>
            <td>{{ $exam['room'] ?? 'À définir' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="bottom-section">
      <table class="notice-row-table">
          <tr>
              <td>
                  <div class="notice-boxs documents">
                      <div class="block-title-new">DOCUMENTS REQUIS</div>
                      <ul>
                          <li>Carte d'identité nationale (CIN)</li>
                          <li>Carte d'étudiant ou attestation</li>
                          <li>Cette convocation imprimée</li>
                      </ul>
                  </div>
              </td>
              <td>
                  <div class="notice-boxs instructions-new">
                      <div class="block-title-new">INSTRUCTIONS & RÈGLEMENT</div>
                      <ul>
                          <li>Arriver au moins 15 minutes avant l'heure.</li>
                          <li>Téléphones portables et appareils électroniques strictement interdits.</li>
                          <li>Toute tentative de fraude entraînera des sanctions disciplinaires.</li>
                      </ul>
                  </div>
              </td>
          </tr>
      </table>

      <div class="footer">
        Document généré le {{ $generatedAt->format('d/m/Y') }} à {{ $generatedAt->format('H:i') }} —
        {{ count($exams) }} examen(s)
        <div class="reference">
          RÉFÉRENCE DU DOCUMENT : {{ strtoupper(substr(md5($student->cne . count($exams) . $generatedAt), 0, 16)) }}
        </div>
      </div>
    </div>
  </div>
</body>
</html>