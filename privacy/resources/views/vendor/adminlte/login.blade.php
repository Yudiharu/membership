@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    <link rel="icon" type="image/png" href="/gui_inventory_laravel/css/logo_gui.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/gui_inventory_laravel/css/logo_gui.png" sizes="32x32">
    @yield('css')
@stop

@section('body_class', 'login-page')
<style>
    .login-box-body {
        background-color: #FBD603;
    }
</style>
@section('body')
<style>
.body_class, .login-page, .content {
    background: url("nightbeach.jpg");
    background-size:cover;
    overflow: hidden;
}

.login-box {
    opacity: 0.85;
}

.login-box-body {
    background-color: black;
}

.login-box-msg {
    color: white;
}
</style>
<br><br>
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}"><font style="color: black; text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #FBBC5C, 0 0 40px #FBBC5C, 0 0 50px #FBBC5C, 0 0 60px #FBBC5C, 0 0 70px #FBBC5C;"><b><i>Sistem Data Tenaga Kerja</i></b></font></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Masukkan Username dan Password anda</p>
            <form action="{{ url(config('adminlte.login_url', 'login')) }}" method="post">
                {!! csrf_field() !!}
                <div class="form-group has-feedback {{ $errors->has('username') ? 'has-error' : '' }}">
                    <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="User Name">
                    <span class="glyphicon glyphicon-globe form-control-feedback"></span>
                    @if ($errors->has('username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                    <input type="password" name="password" class="form-control"
                           placeholder="{{ trans('adminlte::adminlte.password') }}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <!--<div class="checkbox icheck">-->
                        <!--    <label>-->
                        <!--        <input type="checkbox" name="remember"> {{ trans('adminlte::adminlte.remember_me') }}-->
                        <!--    </label>-->
                        <!--</div>-->
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn bg-white btn-block btn-flat">Login</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <!-- <div class="auth-links">
                <a href="{{ url(config('adminlte.password_reset_url', 'password/reset')) }}"
                   class="text-center"
                >{{ trans('adminlte::adminlte.i_forgot_my_password') }}</a>
                <br>
                @if (config('adminlte.register_url', 'register'))
                    <a href="{{ url(config('adminlte.register_url', 'register')) }}"
                       class="text-center"
                    >{{ trans('adminlte::adminlte.register_a_new_membership') }}</a>
                @endif
            </div> -->
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
