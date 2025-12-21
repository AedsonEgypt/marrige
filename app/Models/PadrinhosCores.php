<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PadrinhosCores
 *
 * @property string id
 * @property string user_id
 * @property string descricao_cor
 * @property string cor
 * @property string cor_fonte
 * @property string flg_selecionado
 */

class PadrinhosCores extends Model {

    protected $table = 'padrinhos_cores';

    public $timestamps = false;

    protected $hidden = [];

    protected $casts = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

}
