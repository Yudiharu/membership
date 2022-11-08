@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    <link rel="icon" type="image/png" href="/gui_inventory_laravel/css/logo_gui.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/gui_inventory_laravel/css/logo_gui.png" sizes="32x32">
    @yield('css')
@stop

@section('body_class', 'login-page')

@section('body')
<style>
.body_class, .login-page, .content {
    background: url("warehouse.jpg");
    background-size:cover;
    overflow: hidden;
}
</style>
<br><br>
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('adminlte::adminlte.start_message') }}</p>
            <form method="POST" action="{{ route('start.go_to') }}">
                {!! csrf_field() !!}
                <div class="form-group">
                    <select style="width: 100%" name="company" class="form-control">
                        <?php
                          foreach($Company as $item){
                        ?>
                          <option value="<?php echo $item->kode_company; ?>">
                              <?php echo $item->nama_company; ?>
                          </option>
                        <?php
                          }
                        ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <button type="submit"
                                class="btn btn-primary btn-block btn-flat">{{ trans('adminlte::adminlte.go_to') }}
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
    @yield('js')
@stop
