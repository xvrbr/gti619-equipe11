@extends('master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Journaux de sécurité</h2>
                </div>

                <div class="card-body">
                    <!-- Filtres -->
                    <form method="GET" action="{{ route('admin.security-logs.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="event_type">Type d'événement</label>
                                    <select name="event_type" id="event_type" class="form-control">
                                        <option value="">Tous</option>
                                        @foreach($eventTypes as $type)
                                            <option value="{{ $type }}" {{ request('event_type') == $type ? 'selected' : '' }}>
                                                @switch($type)
                                                    @case('login_success')
                                                        Connexion réussie
                                                        @break
                                                    @case('login_failure')
                                                        Échec de connexion
                                                        @break
                                                    @case('password_change')
                                                        Changement de mot de passe
                                                        @break
                                                @endswitch
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="username">Nom d'utilisateur</label>
                                    <input type="text" name="username" id="username" class="form-control" value="{{ request('username') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_from">Date début</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_to">Date fin</label>
                                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Filtrer</button>
                                <a href="{{ route('admin.security-logs.index') }}" class="btn btn-secondary">Réinitialiser</a>
                            </div>
                        </div>
                    </form>

                    <!-- Table des logs -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Utilisateur</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            @switch($log->event_type)
                                                @case('login_success')
                                                    <span class="badge bg-success">Connexion réussie</span>
                                                    @break
                                                @case('login_failure')
                                                    <span class="badge bg-danger">Échec de connexion</span>
                                                    @break
                                                @case('password_change')
                                                    <span class="badge bg-info">Changement de mot de passe</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ $log->username }}</td>
                                        <td>{{ $log->description }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
