@extends('master')

@section('content')
<div class="container">
    <h2 class="mb-4">Paramètres de sécurité</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Paramètres de connexion</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.security.login') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="max_login_attempts">Nombre maximum de tentatives de connexion</label>
                            <input type="number" class="form-control" id="max_login_attempts"
                                   name="max_login_attempts" value="{{ $settings['max_login_attempts'] }}" min="1">
                            @error('max_login_attempts')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="lock_duration_minutes">Durée de verrouillage (minutes)</label>
                            <input type="number" class="form-control" id="lock_duration_minutes"
                                   name="lock_duration_minutes" value="{{ $settings['lock_duration_minutes'] }}" min="1">
                            @error('lock_duration_minutes')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer les paramètres de connexion</button>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Politique de mot de passe</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.security.password') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="password_min_length">Longueur minimale</label>
                            <input type="number" class="form-control" id="password_min_length"
                                   name="password_min_length" value="{{ $settings['password_min_length'] }}" min="1">
                            @error('password_min_length')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="password_history_count">Nombre de mots de passe à mémoriser</label>
                            <input type="number" class="form-control" id="password_history_count"
                                   name="password_history_count" value="{{ $settings['password_history_count'] }}" min="1">
                            @error('password_history_count')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_expiration_days">Expiration du mot de passe (jours)</label>
                            <input type="number" class="form-control" id="password_expiration_days"
                                   name="password_expiration_days"
                                   value="{{ $settings['password_expiration_days'] }}" min="0">
                            @error('password_expiration_days')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Nombre de jours avant l'expiration du mot de passe. Mettre 0 pour désactiver l'expiration.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <label class="mb-2">Classes de caractères requises :</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="password_require_uppercase"
                                   name="password_require_uppercase" value="true"
                                   {{ $settings['password_require_uppercase'] === 'true' ? 'checked' : '' }}>
                            <label class="form-check-label" for="password_require_uppercase">
                                Lettres majuscules (A-Z)
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="password_require_lowercase"
                                   name="password_require_lowercase" value="true"
                                   {{ $settings['password_require_lowercase'] === 'true' ? 'checked' : '' }}>
                            <label class="form-check-label" for="password_require_lowercase">
                                Lettres minuscules (a-z)
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="password_require_numbers"
                                   name="password_require_numbers" value="true"
                                   {{ $settings['password_require_numbers'] === 'true' ? 'checked' : '' }}>
                            <label class="form-check-label" for="password_require_numbers">
                                Chiffres (0-9)
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="password_require_special"
                                   name="password_require_special" value="true"
                                   {{ $settings['password_require_special'] === 'true' ? 'checked' : '' }}>
                            <label class="form-check-label" for="password_require_special">
                                Caractères spéciaux (!@#$%^&*...)
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Enregistrer la politique de mot de passe</button>
            </form>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
            Gérer les utilisateurs
        </a>
    </div>
</div>
@endsection
