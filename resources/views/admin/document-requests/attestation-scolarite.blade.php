<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Attestation de Scolarité</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'brawler', serif;
            font-weight: 400;
            font-style: normal;
            font-size: 12pt;
            line-height: 1.6;
            padding: 40px 60px;
        }

        .header {
            text-align: center;
            margin-bottom: 60px;
        }

        .logo {
            max-width: 300px;
            height: auto;
            margin: 0 auto;
        }

        .title {
            font-size: 18pt;
            font-weight: bold;
            text-align: center;
            margin: 40px 0 0 0;
            text-decoration: underline;
        }

        .content {
            /* margin: 40px 0; */
            text-align: justify;
            line-height: 2;
        }



        .student-info {
            padding: 20px; */
            /* margin: 30px 0;
            /* border: 2px solid #333; */
            /* background: #f9f9f9; */
        }

        .student-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .student-info td {
            padding: 6px;
            font-size: 11pt;
        }

        .info-label {
            font-weight: bold;
            width: 180px;
        }

        .enrollment-text {
            /* margin: 20px 0; */
            /* text-align: center; */
            /* font-size: 13pt; */
            /* font-weight: bold; */
        }

        .footer {
            margin-top: 50px;
        }

        .footer-location {
            text-align: right;
            margin-bottom: 40px;
            font-size: 12pt;
        }

        .signature-section {
            text-align: center;
            margin-top: 30px;
        }

        .signature-title {
            font-weight: bold;
            /* font-size: 13pt; */
            margin-bottom: 100px;
        }

        .signature-name {
            font-size: 12pt;
        }

        .arabic {
            font-family: 'amiri';
            direction: rtl;
            text-align: center;
            font-weight: bold;
            font-size: 22pt;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ storage_path('app/public/logos/logo_fac_fr.png') }}" alt="Logo Faculté" class="logo">
    </div>

    <div class="title">ATTESTATION DE SCOLARITÉ</div>
    <div class="arabic">شهادة دراسية</div>

    <div class="dean-intro">
        Le Doyen de la Faculté des Sciences Appliquées Ait Melloul, atteste que l'étudiant(e):
    </div>


    <div class="student-info">
        <table>
            <tr>
                <td class="info-label">Nom et Prénom:</td>
                <td>{{ strtoupper($student->nom) }} {{ ucfirst(strtolower($student->prenom)) }}</td>
            </tr>
            <tr>
                <td class="info-label">CNE:</td>
                <td>{{ $student->cne }}</td>
            </tr>
            <tr>
                <td class="info-label">CIN:</td>
                <td>{{ $student->cin ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="info-label">Code APOGEE:</td>
                <td>{{ $student->apogee ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="info-label">Date de naissance:</td>
                <td>{{ $student->date_naissance ? \Carbon\Carbon::parse($student->date_naissance)->format('d/m/Y') : 'N/A' }}</td>
            </tr>
            <tr>
                <td class="info-label">Lieu de naissance:</td>
                <td>{{ $student->lieu_naissance ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>
    @if($currentEnrollment)
    <div class="enrollment-text">
        Poursuit ses études à la <span style="font-weight:bold">{{ $currentEnrollment->year_label }}</span> de <span style="font-weight:bold">{{ $currentEnrollment->filiere->label_fr ?? '--------' }}</span>
        @if($currentEnrollment->academicYear)
           au titre de l'année universitaire: {{ $currentEnrollment->academicYear->start_year }}/{{ $currentEnrollment->academicYear->end_year }}
        @endif
    </div>
    @else
    <div class="enrollment-text">
        ----------
    </div>
    @endif



    <div class="footer">
        <div class="footer-location">
            Fait à Ait Melloul, le {{ $date }}
        </div>

        <div class="signature-section">
            <div class="signature-title">
                Le Doyen
            </div>
            <div class="signature-name">
                <!-- Dean's name and signature will be added here -->
            </div>
        </div>


    </div>

    <div style="font-size: 10pt; position: absolute; bottom: 25px;">
        <p>La présente attestation est délivrée à l'intéressé(e) pour servir et valoir ce que de droit.</p>
    </div>
</body>
</html>
