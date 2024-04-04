@extends('layouts.app')
@section('content')
    <main class="container">
        <section>
            <div class="titlebar">
                <h1>Alunos</h1>
                <a href="{{ route('alunos.create') }}" class='btn-link'>Novo aluno</a>
            </div>
            @if ($message = Session::get('success'))
                <script type="text/javascript">
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    Toast.fire({
                        icon: "success",
                        title: "Aluno cadastrado com sucesso"
                    });
                </script>
            @endif
            <div class="table">
                <div class="table-filter">
                    <div>
                        <ul class="table-filter-list">
                            <li>
                                <p class="table-filter-link link-active">Todos</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <form action="{{ route('alunos.index') }}" accept-charset="UTF-8" role="search" method="get">
                    <div class="table-search">
                        <div>
                            <button class="search-select">
                                Procurar
                            </button>
                            <span class="search-select-arrow">
                                <i class="fas fa-caret-down"></i>
                            </span>
                        </div>
                        <div class="relative">
                            <input class="search-input" type="text" name="search" value="{{ request('search') }}"
                                placeholder="Ex: Joao da Silva..." value="{{ request('search') }}">
                        </div>
                    </div>
                </form>
                <div class="table-student-head">
                    <p></p>
                    <p>Nome</p>
                    <p>Escola</p>
                    <p>Turno</p>
                    <p></p>
                </div>
                <div class="table-student-body">
                    @if (count($alunos) > 0)
                        @foreach ($alunos as $aluno)
                            <img src="{{ asset('fotos/' . $aluno->foto) }}" />
                            <p>{{ $aluno->nome }}</p>
                            <p>{{ $aluno->escola }}</p>
                            <p>{{ $aluno->turno }}</p>
                            <div>


                                <a href="{{ route('alunos.edit', $aluno->id) }}" class="btn-link btn btn-success">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <button class="btn btn-danger">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </div>
                        @endforeach
                    @else
                        <p>Aluno não encontrado</p>
                    @endif
                </div>
                <div class="table-paginate">
                    {{ $alunos->links('layouts.pagination') }}
                </div>
            </div>
        </section>
    </main>
@endsection
