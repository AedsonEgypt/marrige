<?php

namespace App\Http\Controllers;

use App\Models\PadrinhosCores;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PadrinhosController extends Controller {

    public function handle( Request $request ) {
        switch ($request->method()) {
            case 'GET':
                $padrinhos = User::where('role_id', '=', '2')->get();
                foreach ($padrinhos as $padrinho) {
                    if (empty($padrinho->imagem)) {
                        $padrinho->imagem = null;
                    } else {
                        $padrinho->imagem = Cloudinary::getImage('img/padrinhos/' . $padrinho->imagem)->toUrl();
                    }
                }

                return $padrinhos->toArray();
                break;

            default:
                throw new Exception('Método da Requisição não está programado para ser procesado');
                break;
        }

    }

    public function getCores(Request $request) {
        $cores = PadrinhosCores::with('user')->get()->map(function ($cor) {
            $cor->user_name = $cor->user ? $cor->user->name : null;
            return $cor;
        });
        return $cores->toArray();
    }

    public function selecionarCor(Request $request) {
        $cor = PadrinhosCores::where('id', $request->id)->first();

        if (!$cor)
            throw new Exception('Cor não encontrada');

        if ($cor->flg_selecionado && $cor->user_id != Auth::user()->id)
            throw new Exception('Cor já selecionada por outro usuário');

        $jaSelecionada = PadrinhosCores::where('user_id', Auth::user()->id)
            ->where('flg_selecionado', true)
            ->first();

        if ($jaSelecionada && $jaSelecionada->id != $cor->id)
            throw new Exception('Você já selecionou a cor "' . $jaSelecionada->descricao_cor . '". Deselecione-a antes de selecionar outra.');

        if ($cor->flg_selecionado && $cor->user_id == Auth::user()->id)
            return TRUE;

        $cor->user_id = Auth::user()->id;
        $cor->flg_selecionado = true;
        $cor->save();

        return TRUE;
    }

    public function desselecionarCor(Request $request) {
        $cor = PadrinhosCores::where('id', $request->id)->first();

        if (!$cor)
            throw new Exception('Cor não encontrada');

        if (!$cor->flg_selecionado)
            throw new Exception('Cor não selecionada');

        if ($cor->user_id != Auth::user()->id && Auth::user()->role_id != 1)
            throw new Exception('Você não pode desselecionar essa cor');

        $cor->user_id = null;
        $cor->flg_selecionado = false;
        $cor->save();

        return TRUE;
    }

}
