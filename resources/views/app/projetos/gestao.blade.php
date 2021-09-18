@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">Adicionar projeto</div>

                <div class="card-body">
                    <!--{{auth()->user()->perfil}}-->
                    <form method="post" action="{{ route('project.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Usuário</label>
                            <select class="form-control" name="user_id" aria-label="Default select example">
                                <option selected disabled>Selecione um usuário</option>
                                <option value="1">Usuário 01</option>
                                <option value="2">Usuário 02</option>
                                <option value="3">Usuário 03</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descrição (máx 1000 caracteres)</label>
                            <textarea class="form-control" placeholder="Descrição do projeto." style="height: 100px" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Iniciado em</label>
                            <input type="date" class="form-control" name="started_at">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Finalizado em</label>
                            <input type="date" class="form-control" name="finished_at">
                        </div>

                        <button type="submit" class="btn btn-sm btn-success float-right px-3">Cadastrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
