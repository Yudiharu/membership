<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;
use DB;
use Carbon;

class Bank extends Model
{
    use AuditableTrait;
    
    protected $connection = 'mysql2';
    
    protected $table = 'bank';

    protected $primaryKey = 'kode_bank';

    public $incrementing = false;
    
}
