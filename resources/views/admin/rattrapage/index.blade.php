@extends('admin.layouts.app')

@section('title', 'Gestion Rattrapage')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Gestion des Rattrapages</h2>
                    <p class="text-muted mb-0">Justification d'absences et convocations</p>
                </div>
                <div>
                    <a href="{{ route('admin.grades.rattrapage.justification-template') }}" class="btn btn-outline-primary">
                        <i class="fas fa-download me-2"></i>Télécharger Template
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bulkJustifyModal">
                        <i class="fas fa-upload me-2"></i>Import Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white mb-1">Étudiants RATT</h6>
                            <h3 class="mb-0">{{ $stats['total_ratt'] }}</h3>
                        </div>
                        <i class="fas fa-user-clock fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white mb-1">Absents (ABI)</h6>
                            <h3 class="mb-0">{{ $stats['total_abi'] }}</h3>
                        </div>
                        <i class="fas fa-user-times fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white mb-1">Justifiés Aujourd'hui</h6>
                            <h3 class="mb-0">{{ $stats['justified_today'] }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.grades.rattrapage.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Année Universitaire</label>
                    <input type="number" name="academic_year" class="form-control" value="{{ $academicYear }}" min="2020" max="2030">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Session</label>
                    <select name="session" class="form-select">
                        <option value="normal" {{ $session == 'normal' ? 'selected' : '' }}>Normale</option>
                        <option value="rattrapage" {{ $session == 'rattrapage' ? 'selected' : '' }}>Rattrapage</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filière</label>
                    <select name="filiere_id" class="form-select">
                        <option value="">Toutes</option>
                        {{-- Add filieres dynamically --}}
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Students Table --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Étudiants Éligibles au Rattrapage</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Apogée</th>
                            <th>Étudiant</th>
                            <th>Module</th>
                            <th>Note</th>
                            <th>Résultat</th>
                            <th>Justifié?</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grades as $grade)
                            @php
                                $student = $grade->moduleEnrollment->student ?? null;
                                $module = $grade->moduleEnrollment->module ?? null;
                            @endphp
                            @if($student && $module)
                            <tr>
                                <td><strong>{{ $student->apogee }}</strong></td>
                                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                <td>
                                    <div>{{ $module->label }}</div>
                                    <small class="text-muted">{{ $module->code }}</small>
                                </td>
                                <td>
                                    @if($grade->grade)
                                        <span class="badge bg-{{ $grade->grade >= 10 ? 'success' : 'danger' }}">
                                            {{ number_format($grade->grade, 2) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($grade->result == 'RATT')
                                        <span class="badge bg-warning">RATT</span>
                                    @elseif($grade->result == 'ABI')
                                        <span class="badge bg-secondary">ABI</span>
                                    @endif
                                </td>
                                <td>
                                    @if($grade->is_absence_justified)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Oui
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times me-1"></i>Non
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($grade->result == 'ABI' && !$grade->is_absence_justified)
                                        <button type="button" class="btn btn-sm btn-success" 
                                                onclick="showJustifyModal({{ $grade->id }}, '{{ $student->first_name }} {{ $student->last_name }}', '{{ $module->label }}')">
                                            <i class="fas fa-check me-1"></i>Justifier
                                        </button>
                                    @elseif($grade->is_absence_justified)
                                        <form action="{{ route('admin.grades.rattrapage.unjustify', $grade->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-warning" 
                                                    onclick="return confirm('Annuler la justification?')">
                                                <i class="fas fa-undo me-1"></i>Annuler
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun étudiant trouvé</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($grades->hasPages())
        <div class="card-footer">
            {{ $grades->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Justify Modal --}}
<div class="modal fade" id="justifyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="justifyForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Justifier une Absence</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Étudiant:</strong> <span id="studentName"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Module:</strong> <span id="moduleName"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Raison (optionnel)</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Ex: Certificat médical"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Document Justificatif (optionnel)</label>
                        <input type="file" name="document" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">PDF, JPG, PNG (max 2MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Justifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Bulk Justify Modal --}}
<div class="modal fade" id="bulkJustifyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.grades.rattrapage.bulk-justify') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Import Excel - Justifications</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Format: apogee | code_module | justification_reason
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Année Universitaire</label>
                        <input type="number" name="academic_year" class="form-control" value="{{ $academicYear }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Session</label>
                        <select name="session" class="form-select" required>
                            <option value="normal">Normale</option>
                            <option value="rattrapage">Rattrapage</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fichier Excel</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Importer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showJustifyModal(gradeId, studentName, moduleName) {
    document.getElementById('studentName').textContent = studentName;
    document.getElementById('moduleName').textContent = moduleName;
    document.getElementById('justifyForm').action = `/admin/grades/rattrapage/justify/${gradeId}`;
    new bootstrap.Modal(document.getElementById('justifyModal')).show();
}
</script>
@endpush
@endsection
