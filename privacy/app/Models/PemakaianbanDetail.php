<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class PemakaianbanDetail extends Model
{
    //
    use AuditableTrait;

    protected $connection = 'mysqlinvpbm';

    protected $table = 'pemakaianban_detail';

    public $incrementing = false;

}
