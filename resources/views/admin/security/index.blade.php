@extends('master')

@section('content')
<div class="container">
    <h2 class="mb-4">Paramètres de sécurité</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Paramètres de connexion</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.security.update') }}" method="POST">
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
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
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
