<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Relevé de notes</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td, th { border: 1px solid #ccc; padding: 8px; }
    </style>
</head>
<body>
    <h1>Relevé de notes</h1>
    <p><strong>Étudiant :</strong> {{ $student->full_name }}</p>
    <table>
        <thead>
            <tr>
                <th>Année académique</th>
                <th>Semestres</th>
                <th>Filière</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($years as $year)
            <tr>
                <td>{{ $year->label }}</td>
                <td>{{ $year->semesters }}</td>
                <td>{{ $year->major }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>