<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Décharge - {{ $demande->reference_number }}</title>

    <style>
        @font-face {
            font-family: 'brawler';
            src: url('{{ public_path("fonts/brawler/brawler-regular.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'brawler';
            src: url('{{ public_path("fonts/brawler/brawler-bold.ttf") }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
    </style>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "brawler", serif;
            font-weight: 400;
            font-style: normal;
            font-size: 13pt;
            line-height: 1.8;
            padding: 50px 60px;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .header-left {
            display: table-cell;
            width: 70%;
            vertical-align: middle;
        }

        .header-right {
            display: table-cell;
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
            font-size: 32pt;
            font-weight: bold;
            text-align: center;
            /* margin: 20px 0 15px 0; */
        }

        .subtitle {
            font-size: 20pt;
            text-align: center;
            margin-bottom: 30px;
        }

        .info-section {
            margin: 20px 0;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 200px;
            padding-right: 20px;
        }

        .info-value {
            display: table-cell;
        }

        .declaration {
            margin: 8px 0 5px 0;
            line-height: 2;
        }

        .document-list {
            margin: 5px 0 5px 10px;
        }

/*

        .operator-section {
            margin: 30px 0;
        } */

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
            font-size: 14pt;
        }

        .signature-section {
            text-align: left;
            margin-top: 40px;
        }

        .signature-label {
            /* margin-bottom: 80px; */
            font-size: 13pt;
        }

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
        <div class="header-left">
            <img src="{{ storage_path('app/public/logos/logo_fac_fr.png') }}" alt="Logo Faculté" class="logo">
        </div>
        <div class="header-right">
            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" class="qrcode">
        </div>
    </div>

    <div class="title">Décharge</div>
    {{-- <div class="subtitle">Retrait de dossier d'inscription</div> --}}

    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Nom:</div>
            <div class="info-value">{{ strtoupper($student->nom ?? 'N/A') }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Prénom:</div>
            <div class="info-value">{{ strtoupper($student->prenom ?? 'N/A') }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">CNE:</div>
            <div class="info-value">{{ $student->cne ?? 'N/A' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">CIN:</div>
            <div class="info-value">{{ $student->cin ?? 'N/A' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">APOGEE:</div>
            <div class="info-value">{{ $student->apogee ?? 'N/A' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Filière:</div>
            <div class="info-value">{{ $student->programEnrollments->first()->filiere->label_fr ?? 'N/A' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Année d'inscription:</div>
            <div class="info-value">{{ $demande->academic_year }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Pièce Fournie:</div>
            <div class="info-value">{{ $demande->piece_fournie ?? 'Carte d\'étudiant' }}</div>
        </div>
    </div>

    <div class="declaration">
        Je soussigné(e) avoir retiré les pièces suivantes:
    </div>

    <div class="document-list">
        <div class="document-item">
            • <strong>{{ $document->label_fr ?? 'N/A' }}</strong>  @if($demande->semester)
            <span class="document-item">
               ( {{ $demande->semester }} )
            </span>
        @endif : {{ ucfirst($demande->retrait_type) }}

        </div>

        </div>
{{-- 
    @if($demande->isPermanent() && $document->isDeposited())
        <div class="warning-box">
            <div class="warning-title">⚠ ATTENTION - RETRAIT DÉFINITIF</div>
            <div class="warning-text">
                Ce retrait est <strong>DÉFINITIF</strong>. En retirant ce document original,
                je reconnais que mon statut d'étudiant actif sera suspendu et que je ne pourrai
                plus bénéficier des services liés à cette inscription.
            </div>
            <div class="warning-text">
                Je déclare avoir été informé(e) des conséquences de ce retrait définitif
                et accepte toutes les conditions qui en découlent.
            </div>
        </div>
    @elseif($demande->isTemporaire() && $demande->must_return_by)
        <div class="info-row" style="margin-top: 30px;">
            <div class="info-label">Date limite de retour:</div>
            <div class="info-value"><strong>{{ $demande->must_return_by->format('d/m/Y') }}</strong></div>
        </div>
    @endif --}}

    <div class="operator-section">
        <div class="info-row">
            <div class="info-label">Opérateur:</div>
            <div class="info-value">{{ $admin->name ?? '' }}</div>
        </div>
    </div>

    {{-- <div class="notes-section">
        <div class="notes-label">NB:</div>
        <div class="notes-lines"></div>
        <div class="notes-lines"></div>
        <div class="notes-lines"></div>
    </div> --}}

    <div class="footer">
        <div class="footer-location">
               Ait Melloul , le {{ $date }}
        </div>

        <div class="signature-section">
            <div class="signature-label">
                Signature de l'étudiant(e): <span
                    class="signature-line">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </div>
        </div>
    </div>
</body>

</html>
