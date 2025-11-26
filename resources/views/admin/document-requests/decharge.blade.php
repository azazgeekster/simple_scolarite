<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Décharge - {{ $demande->reference_number }}</title>
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
            font-size: 11pt;
            line-height: 1.8;
            padding: 50px 60px;
        }

        .header {
            width: 100%;
            margin-bottom: 8px;
        }

        .header table {
            width: 100%;
        }

        .header-left {
            width: 70%;
            vertical-align: middle;
        }

        .header-right {
            width: 30%;
            text-align: right;
            vertical-align: middle;
        }

        .logo {
            max-width: 280px;
            height: auto;
        }

        .qrcode {
            max-width: 100px;
            height: auto;
            border: 2px solid #000;
            padding: 5px;
        }

        .title {
            font-size: 20pt;
            font-weight: bold;
            text-align: center;
            margin-top: 50px;
            line-height: 1;
        }

        .arabic {
            font-family: 'amiri';
            direction: rtl;
            text-align: center;
            font-weight: bold;
            font-size: 20pt;
            /* margin-bottom: 20px; */
        }

        .info-section {
            margin: 20px 0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-label {
            font-weight: bold;
            width: 200px;
            padding-right: 20px;
            padding-bottom: 5px;
        }

        .info-value {
            padding-bottom: 5px;
        }

        .declaration {
            margin: 8px 0 5px 0;
            line-height: 2;
        }

        .document-list {
            margin: 5px 0 5px 10px;
        }

        .notes-section {
            margin: 40px 0;
            border-top: 2px solid #000;
            padding-top: 20px;
        }

        .notes-label {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .notes-lines {
            border-bottom: 1px solid #999;
            height: 30px;
            margin: 10px 0;
        }

        .footer {
            margin-top: 40px;
        }

        .footer-location {
            text-align: right;
            margin-bottom: 80px;
            /* font-size: 14pt; */
        }

        .signature-section {
            text-align: left;
            margin-top: 40px;
        }

        /* .signature-label {
            font-size: 13pt;
        } */

        .signature-line {
            border-bottom: 1px dotted #333;
            width: 350px;
            display: inline-block;
        }

        .warning-box {
            border: 3px solid #c00;
            background: #fff5f5;
            padding: 20px;
            margin: 30px 0;
        }

        .warning-title {
            color: #c00;
            font-weight: bold;
            font-size: 15pt;
            margin-bottom: 15px;
        }

        .warning-text {
            line-height: 1.6;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td class="header-left">
                    <img src="{{ storage_path('app/public/logos/logo_fac_fr.png') }}" alt="Logo Faculté" class="logo">
                </td>
                <td class="header-right">
                    <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" class="qrcode">
                </td>
            </tr>
        </table>
    </div>

    <div class="title">Décharge</div>
    <div class="arabic">سحب</div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td class="info-label">Nom:</td>
                <td class="info-value">{{ strtoupper($student->nom ?? 'N/A') }}</td>
            </tr>
            <tr>
                <td class="info-label">Prénom:</td>
                <td class="info-value">{{ strtoupper($student->prenom ?? 'N/A') }}</td>
            </tr>
            <tr>
                <td class="info-label">CNE:</td>
                <td class="info-value">{{ $student->cne ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="info-label">CIN:</td>
                <td class="info-value">{{ $student->cin ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="info-label">APOGEE:</td>
                <td class="info-value">{{ $student->apogee ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="info-label">Filière:</td>
                <td class="info-value">{{ $student->programEnrollments->first()->filiere->label_fr ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="info-label">Année d'inscription:</td>
                <td class="info-value">{{ $demande->academic_year }}</td>
            </tr>
            <tr>
                <td class="info-label">Pièce Fournie:</td>
                <td class="info-value">{{ $demande->piece_fournie ?? 'Carte d\'étudiant' }}</td>
            </tr>
        </table>
    </div>

    <div class="declaration">
        Je soussigné(e) avoir retiré les pièces suivantes:
    </div>

    <div class="document-list">
        <div class="document-item">
            • <strong>{{ $document->label_fr ?? 'N/A' }}</strong>
            @if($demande->semester)
                <span class="document-item">( {{ $demande->semester }} )</span>
            @endif
            : {{ ucfirst($demande->retrait_type) }}
        </div>
    </div>

    <div class="operator-section">
        <table class="info-table" style="margin-top: 20px;">
            <tr>
                <td class="info-label">Opérateur:</td>
                <td class="info-value">{{ $admin->name ?? '' }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div class="footer-location">
            Ait Melloul, le {{ now()->format("d/m/Y H:i:s")}}
        </div>

        <div class="signature-section">
            <div class="signature-label">
                Signature de l'étudiant(e): <span class="signature-line">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </div>
        </div>
    </div>
</body>
</html>
