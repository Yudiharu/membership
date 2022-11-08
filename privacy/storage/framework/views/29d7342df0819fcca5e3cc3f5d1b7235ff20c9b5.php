<?php $__env->startSection('adminlte_css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/plugins/iCheck/square/blue.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/adminlte/css/auth.css')); ?>">
    <link rel="icon" type="image/png" href="/gui_inventory_laravel/css/logo_gui.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/gui_inventory_laravel/css/logo_gui.png" sizes="32x32">
    <?php echo $__env->yieldContent('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body_class', 'login-page'); ?>
<style>
    .login-box-body {
        background-color: #FBD603;
    }
</style>
<?php $__env->startSection('body'); ?>
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
            <a href="<?php echo e(url(config('adminlte.dashboard_url', 'home'))); ?>"><font style="color: black; text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #FBBC5C, 0 0 40px #FBBC5C, 0 0 50px #FBBC5C, 0 0 60px #FBBC5C, 0 0 70px #FBBC5C;"><b><i>Sistem Data Tenaga Kerja</i></b></font></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Masukkan Username dan Password anda</p>
            <form action="<?php echo e(url(config('adminlte.login_url', 'login'))); ?>" method="post">
                <?php echo csrf_field(); ?>

                <div class="form-group has-feedback <?php echo e($errors->has('username') ? 'has-error' : ''); ?>">
                    <input type="text" name="username" class="form-control" value="<?php echo e(old('username')); ?>" placeholder="User Name">
                    <span class="glyphicon glyphicon-globe form-control-feedback"></span>
                    <?php if($errors->has('username')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('username')); ?></strong>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="form-group has-feedback <?php echo e($errors->has('password') ? 'has-error' : ''); ?>">
                    <input type="password" name="password" class="form-control"
                           placeholder="<?php echo e(trans('adminlte::adminlte.password')); ?>">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <?php if($errors->has('password')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('password')); ?></strong>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <!--<div class="checkbox icheck">-->
                        <!--    <label>-->
                        <!--        <input type="checkbox" name="remember"> <?php echo e(trans('adminlte::adminlte.remember_me')); ?>-->
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
                <a href="<?php echo e(url(config('adminlte.password_reset_url', 'password/reset'))); ?>"
                   class="text-center"
                ><?php echo e(trans('adminlte::adminlte.i_forgot_my_password')); ?></a>
                <br>
                <?php if(config('adminlte.register_url', 'register')): ?>
                    <a href="<?php echo e(url(config('adminlte.register_url', 'register'))); ?>"
                       class="text-center"
                    ><?php echo e(trans('adminlte::adminlte.register_a_new_membership')); ?></a>
                <?php endif; ?>
            </div> -->
        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('adminlte_js'); ?>
    <script src="<?php echo e(asset('vendor/adminlte/plugins/iCheck/icheck.min.js')); ?>"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
    <?php echo $__env->yieldContent('js'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>