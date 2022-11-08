<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Yajra\Auditable\AuditableTrait;

class Pemakaianban extends Model
{
    //
    use AuditableTrait;
    
    protected $connection = 'mysqlinvpbm';
  
    protected $table = 'pemakaianban';
    
}
