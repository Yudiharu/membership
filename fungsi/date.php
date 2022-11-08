<?php 
    function format_tgl($request)
    {
        $data = Carbon\Carbon::parse($request)->format('d-m-Y');
        return $data;
    }
?>