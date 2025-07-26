@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Gestion des mots de passe</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Paramètres système -->
    <div class="card mb-4">
        <div class="card-header">Paramètres de sécurité</div>
        <div class="card-body">
            <form action="{{ route('admin.password-settings') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="max_login_attempts">Nombre maximum de tentatives</label>
                            <input type="number" class="form-control" id="max_login_attempts"
                                   name="max_login_attempts" value="{{ $settings['max_login_attempts'] }}" min="1">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lock_duration_minutes">Durée de verrouillage (minutes)</label>
                            <input type="number" class="form-control" id="lock_duration_minutes"
                                   name="lock_duration_minutes" value="{{ $settings['lock_duration_minutes'] }}" min="1">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Mettre à jour les paramètres</button>
            </form>
        </div>
    </div>

    <!-- Liste des utilisateurs -->
    <div class="card">
        <div class="card-header">Gestion des utilisateurs</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Nom d'utilisateur</th>
                            <th>Dernier changement</th>
                            <th>Tentatives échouées</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->password_changed_at ? $user->password_changed_at->format('d/m/Y H:i') : 'Jamais' }}</td>
                            <td>{{ $user->failed_login_attempts }}</td>
                            <td>
                                @if($user->locked_until && $user->locked_until > now())
                                    <span class="badge bg-danger">Verrouillé</span>
                                @else
                                    <span class="badge bg-success">Actif</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#resetPasswordModal{{ $user->id }}">
                                    Réinitialiser MDP
                                </button>
                                @if($user->locked_until && $user->locked_until > now())
                                    <form action="{{ route('admin.users.unlock', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning">Déverrouiller</button>
                                    </form>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal de réinitialisation de mot de passe -->
                        <div class="modal fade" id="resetPasswordModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.users.reset-password', $user) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Réinitialiser le mot de passe - {{ $user->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="new_password{{ $user->id }}">Nouveau mot de passe</label>
                                                <input type="password" class="form-control"
                                                       id="new_password{{ $user->id }}"
                                                       name="new_password" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Réinitialiser</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
