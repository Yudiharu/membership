<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use App\Models\MasterLokasi;

class TypeInv extends Model
{
    //
    use AuditableTrait;

    protected $connection = 'mysqlpbm';

    protected $table = 'accinv_pbm';

    public $incrementing = false;

}
