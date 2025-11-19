<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PV - {{ $exam->module->code }}</title>
    <style>
        @page {
            margin: 12mm 20mm;
        }

        body {
            font-family: 'roboto', sans-serif;
            font-size: 10pt;
            line-height: 1.2;
            color: #000;
        }

        /* Header with logo */
        .page-header {
            text-align: center;
            margin-bottom: 15px;
            position: relative;
            padding-bottom: 10px;
            border-bottom: 3px double #;
        }

        .page-header img {
            max-width: 100px;
            margin-bottom: 5px;
        }

        .university-name {
            font-size: 10pt;
            margin: 2px 0;
            font-weight: 600;
            color: #1a5490;
            letter-spacing: 0.5px;
        }

        .university-name-ar {
            font-size: 13pt;
            margin: 2px 0;
            direction: rtl;
            font-weight: 600;
            color: #1a5490;
        }

        /* Main title section - UPGRADED */
        .title-section {
            text-align: center;
            margin: 15px 0;
            background-color: #e8f4ff;
            padding: 12px;
            border-radius: 8px;
             color:#111;
        }

        .title-section h1 {
            margin: 0;
            font-size: 22pt;
            font-weight: bold;
            letter-spacing: 2px;
            color:   #1a5490;

        }

        /* Academic year styling - UPGRADED */
        .academic-year {
            text-align: center;
            margin: 15px 0;
            padding: 8px;
            background: linear-gradient(to right, #f8f9fa, #e9ecef, #f8f9fa);
            border-left: 4px solid #1a5490;
            border-right: 4px solid #1a5490;
        }

        .academic-year p {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            color: #1a5490;
        }

        /* Info table styling - UPGRADED */
        .info-table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
            direction: ltr;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .info-table td {
            padding: 8px 12px;
            border: 1px solid #1a5490;
            font-size: 11pt !important;
        }

        .info-table .label {
            font-weight: bold;
            width: 30%;
            background:  linear-gradient(to right, #f8f9fa, #e9ecef, #f8f9fa);
            color: black;
            text-align: left;
            font-size: 11pt !important;
        }

        .info-table .value {
            width: 70%;
            text-align: left;
            background : #f8f9fa;
            font-weight: 600;
            font-size: 11pt !important;
        }

        /* Module info table - UPGRADED */
        .module-table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
            direction: ltr;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .module-table td {
            padding: 10px;
            border: 1px solid #1a5490;
            text-align: center;
            font-size: 13pt;
        }

        .module-table .header-cell {
            font-weight: bold;
            background-color:   #e8f4ff;
            color: #111;
        }

        .module-table .value-cell {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        /* Student list table - LTR */
        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            direction: ltr;
        }

        .student-table th {
            background: linear-gradient(to right, #f8f9fa, #e9ecef, #f8f9fa);
            color: #1a5490;
            padding: 8px 5px;
            text-align: center;
            font-size: 9pt;
            font-weight: bold;
            border: 1px solid #1a5490;
        }

        .student-table td {
            border: 1px solid #1a5490;
            padding: 6px 5px;
            text-align: center;
            font-size: 9pt;
        }

        /* .student-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        } */

        .student-table .text-left {
            text-align: left;
            padding-left: 10px;
        }

        .student-table .exam-number {
            font-weight: bold;
            color: #1a5490;
        }

        /* PAGE 2 - Arabic Section Styling - UPGRADED */
        .pv-header-title {
            text-align: center;
            margin: 12px 0;
            padding: 8px;
            background:  linear-gradient(to right, #f8f9fa, #e9ecef, #f8f9fa);
            border-radius: 8px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.15);
        }

        .pv-header-title h2 {
            display: inline-block;
            margin: 0 15px;
            font-size: 15pt;
            color: #1a5490;
            font-weight: bold;
        }

        /* PV Form Section - COMPACT & UPGRADED */
        .pv-form {
            margin-top: 12px;
            direction: rtl;
            text-align: right;
        }

        .pv-form table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            direction: rtl;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .pv-form td {
            padding: 6px 10px;
            border: 1px solid #111;
            font-size: 10pt;
        }

        .pv-form .form-label {
            font-weight: bold;
            width: 70%;
            text-align: right;
            background-color :#e8f4ff;
            color: #111;
        }

        .pv-form .form-field {
            width: 30%;
            background-color: #fff;
        }

        /* Signature section - SINGLE TABLE - UPGRADED */
        .signature-section {
            margin-top: 15px;
            direction: rtl;
        }

        .signature-title {
            text-align: center;
            margin: 12px 0;
            padding: 6px;
            background:linear-gradient(to right, #f8f9fa, #e9ecef, #f8f9fa);
            color: #1a5490;
            font-weight: bold;
            font-size: 13pt;
            border-radius: 6px;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            direction: rtl;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .signature-table th {
            padding: 6px;
            border: 1px solid #1a5490;
            background: linear-gradient(to right, #f8f9fa, #e9ecef, #f8f9fa);
            color: #1a5490;
            font-weight: bold;
            text-align: center;
            font-size: 10pt;
        }

        .signature-table td {
            padding: 18px 6px;
            border: 1px solid #111;
            height: 45px;
            font-size: 9pt;
            background-color: #f8f9fa;
        }

        /* Notes section - COMPACT - UPGRADED */
        .notes-section {
            margin-top: 10px;
            direction: rtl;
            text-align: right;
        }

        .notes-section p {
            margin: 3px 0;
            font-size: 9pt;
            font-weight: bold;
            color: #111;
        }

        .dotted-line {
            border-bottom: 1px dotted #111;
            margin: 2px 0;
            height: 12px;
        }

        /* Page break */
        .page-break {
            page-break-after: always;
        }

        /* Footer note - COMPACT */
        .footer-note {
            margin-top: 8px;
            padding: 5px;
            font-size: 8pt;
            direction: rtl;
            text-align: right;
            font-style: italic;
            background-color: #fff3cd;
            border-left: 3px solid #ffc107;
        }

        .arabic {
            font-family: 'dejavusans', sans-serif;
            direction: rtl;
            text-align: right;
        }

        /* Apply Arabic font to Arabic content only */
        .ar-section, .pv-form, .signature-section,
        .notes-section, .footer-note, .university-name-ar, .arabic {
            font-family: 'dejavusans', sans-serif;
        }

        /* Decorative elements */
        .corner-decoration {
            position: absolute;
            width: 40px;
            height: 40px;
            border: 2px solid #1a5490;
        }

        .corner-tl {
            top: 0;
            left: 0;
            border-right: none;
            border-bottom: none;
        }

        .corner-tr {
            top: 0;
            right: 0;
            border-left: none;
            border-bottom: none;
        }
    </style>
</head>
<body>
    @foreach($localGroups as $localId => $convocations)
        @php
            $local = $convocations->first()->local;
            $localIndex = $loop->index + 1;
            $totalLocals = $localGroups->count();
        @endphp

        {{-- PAGE 1: French Cover Page - UPGRADED --}}
        <div style="position: relative;">
            <div class="page-header">
                @if(!empty($logoBase64))
                <img src="{{ $logoBase64 }}" alt="Logo" style="width:180px;">
                @endif

            </div>

            <div class="title-section">
                <h1>EXAMEN</h1>
            </div>

            <div class="academic-year">
                <p>ANNEE UNIVERSITAIRE : {{ $exam->academic_year }}/{{ $exam->academic_year + 1 }}</p>
            </div>

            <table class="info-table">
                <tr>
                    <td class="label">PERIODE</td>
                    <td class="value">{{ $exam->season === 'autumn' ? 'AUTOMNE' : 'PRINTEMPS' }}</td>
                </tr>
                <tr>
                    <td class="label">SESSION</td>
                    <td class="value">{{ $exam->session_type === 'normal' ? 'NORMALE' : 'RATTRAPAGE' }}</td>
                </tr>
                <tr>
                    <td class="label">DATE</td>
                    <td class="value">{{ $exam->exam_date->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td class="label">HORAIRE</td>
                    <td class="value">{{ $exam->start_time->format('H:i') }} à {{ $exam->end_time->format('H:i') }}</td>
                </tr>
            </table>

            <table class="module-table">
                <tr>
                    <td class="header-cell" style="width: 20%;">FILIERE</td>
                    <td class="value-cell" style="width: 30%;">{{ $exam->module->filiere->label_fr ?? 'N/A' }}</td>
                    <td class="header-cell" style="width: 20%;">SEMESTRE</td>
                    <td class="value-cell" style="width: 30%;">{{ $exam->semester }}</td>
                </tr>
                <tr>
                    <td class="header-cell" colspan="4">MODULE</td>
                </tr>
                <tr>
                    <td class="value-cell" colspan="4">{{ $exam->module->label }}</td>
                </tr>
                <tr>
                    <td class="header-cell" colspan="4">LOCAL</td>
                </tr>
                <tr>
                    <td class="value-cell" colspan="4">{{ $local->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="header-cell" colspan="2">Effectif Local / Effectif Module</td>
                    <td class="value-cell" colspan="2">{{ $convocations->count() }} / {{ $exam->convocations->count() }}</td>
                </tr>
                <tr>
                    <td class="header-cell" colspan="2">N° Local / Nbr de Locaux Occupés</td>
                    <td class="value-cell" colspan="2">{{ $localIndex }} / {{ $totalLocals }}</td>
                </tr>
            </table>
        </div>

        <div class="page-break"></div>

        {{-- PAGE 2: Arabic PV Form - UPGRADED & OPTIMIZED --}}
        <div class="page-header">
            @if(!empty($logoBase64))
            <img src="{{ $logoBase64 }}" alt="Logo" style="width:180px;">
            @endif

        </div>

        <div class="pv-header-title">
            <h2><em>PV d'Examen</em></h2>
            <h2>محضر الامتحان</h2>
        </div>

        <table class="info-table">
            <tr>
                <td class="label">ANNEE UNIVERSITAIRE</td>
                <td class="value">{{ $exam->academic_year }}-{{ $exam->academic_year + 1 }}</td>
                <td class="label">PERIODE</td>
                <td class="value">{{ $exam->season === 'autumn' ? 'AUTOMNE' : 'PRINTEMPS' }}</td>
            </tr>
            <tr>
                <td class="label">DATE D'EXAMEN</td>
                <td class="value">{{ $exam->exam_date->format('d/m/Y') }}</td>
                <td class="label">SESSION</td>
                <td class="value">{{ $exam->session_type === 'normal' ? 'NORMALE' : 'RATTRAPAGE' }}</td>
            </tr>
            <tr>
                <td class="label" colspan="4" style="text-align: center;">HORAIRE</td>
            </tr>
            <tr>
                <td colspan="4" class="value" style="text-align: center;">{{ $exam->start_time->format('H:i') }} - {{ $exam->end_time->format('H:i') }}</td>
            </tr>
        </table>

        <table class="module-table">
            <tr>
                <td class="header-cell">Filière<br><span style="font-size: 12pt;">المسلك</span></td>
                <td class="header-cell">Semestre<br><span style="font-size: 12pt;">الفصل</span></td>
                <td class="header-cell" colspan="2">Module<br><span style="font-size: 12pt;">الوحدة</span></td>
            </tr>
            <tr>
                <td class="value-cell">{{ $exam->module->filiere->label_fr ?? 'N/A' }}</td>
                <td class="value-cell">{{ $exam->semester }}</td>
                <td class="value-cell" colspan="2">{{ $exam->module->label }}</td>
            </tr>
        </table>

        <table class="module-table" style="direction: rtl;">
            <tr>
                <td class="header-cell" style="width: 25%;">القاعة / المدرج رقم</td>
                <td class="header-cell" style="width: 25%;">عدد طلبة القاعة / المدرج</td>
                <td class="header-cell" style="width: 50%;">العدد الاجمالي للطلبة المسجلين في الوحدة</td>
            </tr>
            <tr>
                <td class="value-cell">{{ $local->name ?? 'N/A' }}</td>
                <td class="value-cell">{{ $convocations->count() }}</td>
                <td class="value-cell">{{ $exam->convocations->count() }}</td>
            </tr>
        </table>

        <div class="pv-form">
            <table>
                <tr>
                    <td class="form-label">عدد الطلبة الممتحنين الحاضرين</td>
                    <td class="form-field"></td>
                </tr>
                <tr>
                    <td class="form-label">عدد الطلبة الممتحنين الحاضرين وغير المسجلين بلائحة الحضور</td>
                    <td class="form-field"></td>
                </tr>
                <tr>
                    <td class="form-label">حالات الغياب</td>
                    <td class="form-field"></td>
                </tr>
                <tr>
                    <td class="form-label">عدد اوراق الامتحان المسترجعة*</td>
                    <td class="form-field"></td>
                </tr>
            </table>
        </div>

        <div class="notes-section">
            <p>ملاحظات</p>
            <div class="dotted-line"></div>
            <div class="dotted-line"></div>

            <p style="margin-top: 8px;">حالات الغش المرفقة بمحضر</p>
            <div class="dotted-line"></div>
        </div>

        <div class="signature-section">
            <div class="signature-title">الأساتذة المشرفين على الحراسة</div>
            <table class="signature-table">
                <thead>
                    <tr>
                        <th style="width: 45%;">الاسم و النسب</th>
                        <th style="width: 25%;">الصفة</th>
                        <th style="width: 30%;">التوقيع</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer-note">
            <p>*يجب التأكد من مطابقة العدد الاجمالي للطلبة الحاضرين بالقاعة و عدد الاوراق المسترجعة</p>
        </div>

        <div class="page-break"></div>

        {{-- PAGE 3: Student List - LTR --}}


        {{-- <table class="info-table" style="margin-bottom: 15px; font-size: 9pt;">
            <tr>
                <td class="label" style="width: 10%;">Module</td>
                <td class="value" style="width: 25%;">{{ $exam->module->label }}</td>
                <td class="label" style="width: 15%;">Date & horaire</td>
                <td class="value" style="width: 20%;">{{ $exam->exam_date->format('d/m/Y') }} - {{ $exam->start_time->format('H:i') }} A {{ $exam->end_time->format('H:i') }}</td>
                <td class="label" style="width: 10%;">filière</td>
                <td class="value" style="width: 10%;">{{ $exam->module->filiere->code ?? 'N/A' }}-{{ $exam->semester }}</td>
                <td class="label" style="width: 5%;">Local</td>
                <td class="value" style="width: 5%;">{{ $local->name ?? 'N/A' }}</td>
            </tr>
        </table> --}}
        <div class="signature-title" style="margin-bottom:15px"> لائحة الطلبة الممتحنين  </div>

        <table class="student-table">
            <thead>
                <tr>
                    <th style="width: 8%;">N°Exam</th>
                    <th style="width: 12%;">Apogee</th>
                    <th style="width: 30%;">Nom</th>
                    <th style="width: 30%;">Prénom</th>
                    <th style="width: 8%;">P/Ab</th>
                    <th style="width: 12%;">Observation</th>
                </tr>
            </thead>
            <tbody>
                @foreach($convocations->sortBy(function($convocation) {
                    // Natural sort: extract the numeric part after the dash
                    $parts = explode('-', $convocation->n_examen);
                    return count($parts) > 1 ? (int)$parts[1] : 0;
                }) as $convocation)
                    @php
                        $student = $convocation->studentModuleEnrollment->student ?? null;
                    @endphp
                    <tr>
                        <td class="exam-number">{{ $convocation->n_examen }}</td>
                        <td>{{ $student->apogee ?? 'N/A' }}</td>
                        <td class="text-left">{{ strtoupper($student->nom ?? 'N/A') }}</td>
                        <td class="text-left">{{ ucfirst(strtolower($student->prenom ?? 'N/A')) }}</td>
                        <td></td>
                        <td style="font-size: 8pt;">{{ $convocation->observations ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>