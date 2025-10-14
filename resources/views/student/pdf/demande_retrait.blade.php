<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demande BAC #{{ $demande->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td, th { border: 1px solid #ccc; padding: 8px; }
    </style>
</head>
<body>
    <h1>Demande de retrait de document</h1>

    <p><strong>Étudiant :</strong> {{ $demande->student->full_name }}</p>
    <p><strong>Date de la demande :</strong> {{ $demande->created_at->format('d/m/Y') }}</p>
    <p><strong>Document :</strong> {{ $demande->document->label_fr }}</p>
    <p><strong>Type de retrait :</strong> {{ ucfirst($demande->retrait_type) }}</p>
    <p><strong>Année académique :</strong> {{ $demande->academicYear->label }}</p>
    {{-- <p><strong>Status :</strong> {{ $demande->status }}</p> --}}
</body>
</html>
