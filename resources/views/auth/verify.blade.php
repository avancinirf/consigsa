@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><i class="bi bi-exclamation-triangle-fill mr-3 text-secondary" style="font-size: 1.3rem;"></i>Velidação de e-mail necessária.</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            Um novo e-mail de verificação foi enviado para você.
                        </div>
                    @endif
                    Antes de prosseguir, por favor, verifique sua caixa de e-mails para acessar o link de validação.
                    Se ainda não recebeu o e-mail,
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">clique aqui para reenviarmos</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
