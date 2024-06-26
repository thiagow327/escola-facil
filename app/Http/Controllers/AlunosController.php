<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alunos;

class AlunosController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 5;

        if (!empty($keyword)) {
            $alunos = Alunos::where('nome', 'LIKE', "%$keyword%")
                ->orWhere('escola', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $alunos = Alunos::latest()->paginate($perPage);
        }
        return view('alunos.index', ['alunos' => $alunos]);
    }

    public function create()
    {
        return view('alunos.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'nome' => 'required',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2028',
        ];

        $messages = [
            'nome.required' => 'O campo "Nome Completo" é obrigatório.',
        ];

        $request->validate($rules, $messages);

        $aluno = new Alunos;

        // Verificar se uma imagem foi enviada
        if ($request->hasFile('foto')) {
            $file_name = time() . '.' . request()->foto->getClientOriginalExtension();
            request()->foto->move(public_path('fotos'), $file_name);
            $aluno->foto = $file_name;
        }

        $aluno->nome = $request->nome;
        $aluno->idade = $request->idade;
        $aluno->observacao_de_saude = $request->observacao_de_saude;
        $aluno->escola = $request->escola;
        $aluno->turno = $request->turno;
        $aluno->nome_do_primeiro_responsavel = $request->nome_do_primeiro_responsavel;
        $aluno->celular_do_primeiro_responsavel = $request->celular_do_primeiro_responsavel;
        $aluno->nome_do_segundo_responsavel = $request->nome_do_segundo_responsavel;
        $aluno->celular_do_segundo_responsavel = $request->celular_do_segundo_responsavel;
        $aluno->endereco = $request->endereco;
        $aluno->valor_da_mensalidade = $request->valor_da_mensalidade;

        $aluno->save();
        return redirect()->route('alunos.index')->with('success', 'Aluno adicionado com sucesso.');
    }

    public function edit($id)
    {
        $aluno = Alunos::findOrFail($id);
        return view('alunos.edit', ['aluno' => $aluno]);
    }

    public function update(Request $request, Alunos $aluno)
    {
        $request->validate([
            'nome' => 'required'
        ]);

        $aluno = Alunos::find($request->hidden_id);

        $aluno->nome = $request->nome;
        $aluno->idade = $request->idade;
        $aluno->observacao_de_saude = $request->observacao_de_saude;
        $aluno->escola = $request->escola;
        $aluno->turno = $request->turno;

        if ($request->hasFile('foto')) {
            $file_name = time() . '.' . $request->foto->getClientOriginalExtension();
            $request->foto->move(public_path('fotos'), $file_name);
            // Excluir a imagem antiga apenas se uma nova imagem for fornecida
            $image_path = public_path('fotos/') . $aluno->foto;
            if (file_exists($image_path)) {
                @unlink($image_path);
            }
            $aluno->foto = $file_name;
        }

        $aluno->nome_do_primeiro_responsavel = $request->nome_do_primeiro_responsavel;
        $aluno->celular_do_primeiro_responsavel = $request->celular_do_primeiro_responsavel;
        $aluno->nome_do_segundo_responsavel = $request->nome_do_segundo_responsavel;
        $aluno->celular_do_segundo_responsavel = $request->celular_do_segundo_responsavel;
        $aluno->endereco = $request->endereco;
        $aluno->valor_da_mensalidade = $request->valor_da_mensalidade;

        $aluno->save();

        return redirect()->route('alunos.index')->with('success', 'Aluno atualizado');
    }

    public function destroy($id)
    {
        $aluno = Alunos::findOrFail($id);
        $image_path = public_path() . "/fotos/";
        $image = $image_path . $aluno->foto;
        if (file_exists($image)) {
            @unlink($image);
        }
        $aluno->delete();
        return redirect('alunos')->with('success', 'Aluno deletado!');
    }
}
