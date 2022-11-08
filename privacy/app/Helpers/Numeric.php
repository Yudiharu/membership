<?php

 function numeric($value){
     return filter_var($value,FILTER_SANITIZE_NUMBER_INT);
 }

 function format_indo($value){
     return number_format($value,'0',',','.');
 }

function tanggal_indo($tgl)
{
    $dt = new \Carbon\Carbon($tgl);
    setlocale(LC_TIME, 'IND');

    return $dt->formatLocalized('%d %B %Y');
}