@extends('master')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestion des utilisateurs</h2>
        <a href="{{ route('admin.security') }}" class="btn btn-secondary">
            Retour aux paramètres de sécurité
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Nom d'utilisateur</th>
                            <th>Rôle</th>
                            <th>Dernier changement MDP</th>
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
                            <td>{{ $user->role }}</td>
                            <td>
                                @if($user->password_changed_at)
                                    {{ $user->password_changed_at->format('d/m/Y H:i') }}
                                @else
                                    Jamais
                                @endif
                            </td>
                            <td>{{ $user->failed_login_attempts }}</td>
                            <td>
                                @if($user->locked_until && $user->locked_until > now())
                                    <span class="badge bg-danger">Verrouillé</span>
                                @else
                                    <span class="badge bg-success">Actif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#resetPasswordModal{{ $user->id }}">
                                        Réinitialiser MDP
                                    </button>
                                    <form action="{{ route('admin.users.toggle-lock', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $user->locked_until && $user->locked_until > now() ? 'btn-warning' : 'btn-danger' }}">
                                            {{ $user->locked_until && $user->locked_until > now() ? 'Déverrouiller' : 'Verrouiller' }}
                                        </button>
                                    </form>
                                </div>
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
                                                       name="new_password" required
                                                       minlength="8">
                                                <small class="form-text text-muted">
                                                    Le mot de passe doit contenir au moins 8 caractères.
                                                </small>
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
