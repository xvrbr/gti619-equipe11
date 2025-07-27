@extends('master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Changer votre mot de passe</span>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('warning'))
                        <div class="alert alert-warning">
                            {{ session('warning') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.change.submit') }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="new_password" class="col-md-4 col-form-label text-md-right">Nouveau mot de passe</label>
                            <div class="col-md-6">
                                <input id="new_password" type="password"
                                       class="form-control @error('new_password') is-invalid @enderror"
                                       name="new_password" required autocomplete="new-password">
                                @error('new_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="new_password_confirmation" class="col-md-4 col-form-label text-md-right">
                                Confirmer le mot de passe
                            </label>
                            <div class="col-md-6">
                                <input id="new_password_confirmation" type="password"
                                       class="form-control"
                                       name="new_password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Changer le mot de passe
                                </button>
                            </div>
                        </div>

                        <div class="mt-3">
                            <strong>Le mot de passe doit respecter les règles suivantes :</strong>
                            <ul class="mb-0">
                                <li>Au moins {{ \App\Models\SystemSetting::getValue('password_min_length', 8) }} caractères</li>
                                @if(\App\Models\SystemSetting::getValue('password_require_uppercase', 'false') === 'true')
                                    <li>Au moins une lettre majuscule</li>
                                @endif
                                @if(\App\Models\SystemSetting::getValue('password_require_lowercase', 'false') === 'true')
                                    <li>Au moins une lettre minuscule</li>
                                @endif
                                @if(\App\Models\SystemSetting::getValue('password_require_numbers', 'false') === 'true')
                                    <li>Au moins un chiffre</li>
                                @endif
                                @if(\App\Models\SystemSetting::getValue('password_require_special', 'false') === 'true')
                                    <li>Au moins un caractère spécial</li>
                                @endif
                                <li>Ne pas être l'un des {{ \App\Models\SystemSetting::getValue('password_history_count', 3) }} derniers mots de passe</li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
