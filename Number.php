<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Number extends Model
{
    protected $table = 'number';

    protected $fillable = [
        'number'
    ];

    protected $primaryKey = 'number';

    public $incrementing = false;

    public function generateNumber($source_text = 'INV')
    {
        $primary_key = $this->getKeyName();
        $get_prefix_1 = '01';
        $get_prefix_2 = strtoupper($source_text);
        $get_prefix_3 = date('my');
        $prefix_result = $get_prefix_1.$get_prefix_2.$get_prefix_3;
        $prefix_result_length = strlen($get_prefix_1.$get_prefix_2.$get_prefix_3);

        $lastRecort = $this->where($primary_key,'like',$prefix_result.'%')->orderBy('created_at', 'desc')->first();

        if ( ! $lastRecort )
            $number = 0;
        else {
            $get_record_prefix = strtoupper(substr($lastRecort->number, 0,$prefix_result_length));
            if ($get_record_prefix == $prefix_result){
                $number = substr($lastRecort->number,$prefix_result_length);
            }else {
                $number = 0;
            }

        }

        $result_number = $prefix_result . sprintf('%06d', intval($number) + 1);

        return $result_number ;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($query){
           $query->number = (new self())->generateNumber('DST');
        });
    }

}
