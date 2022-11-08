<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class PemakaianDetail extends Model
{
    //
    use AuditableTrait;

    protected $connection = 'mysqlinvpbm';

    protected $table = 'pemakaian_detail';

    public $incrementing = false;

}
