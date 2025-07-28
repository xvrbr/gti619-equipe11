@extends('master')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h4>Confirmer votre mot de passe</h4>
        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autofocus>
                @error('password')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Confirmer</button>
        </form>
    </div>
</div>
@endsection