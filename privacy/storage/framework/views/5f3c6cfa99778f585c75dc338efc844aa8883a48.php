<?php $__env->startSection('adminlte_css'); ?>
    <link rel="stylesheet"
          href="<?php echo e(asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')); ?> ">
    <link rel="icon" type="image/png" href="/gui_inventory_laravel/css/logo_gui.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/gui_inventory_laravel/css/logo_gui.png" sizes="32x32">
    <?php echo $__env->yieldPushContent('css'); ?>
    <?php echo $__env->yieldContent('css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' . (config('adminlte.layout') ? [
    'boxed' => 'layout-boxed',
    'fixed' => 'fixed',
    'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : '')); ?>

<?php $__env->startSection('body'); ?>
    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">
            <?php if(config('adminlte.layout') == 'top-nav'): ?>
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="<?php echo e(url(config('adminlte.dashboard_url', 'home'))); ?>" class="navbar-brand">
                            <?php echo config('adminlte.logo', '<b>Admin</b>LTE'); ?>

                        </a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>



                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">

                        <ul class="nav navbar-nav">
                            <?php echo $__env->renderEach('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item'); ?>
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
            <?php else: ?>
            <!-- Logo -->
            <a href="<?php echo e(url(config('adminlte.dashboard_url', 'home'))); ?>" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><?php echo config('adminlte.logo_mini', '<b>A</b>LT'); ?></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><?php echo config('adminlte.logo', '<b>Admin</b>LTE'); ?></span>


            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only"><?php echo e(trans('adminlte::adminlte.toggle_navigation')); ?></span>
                </a>

                        <span class="pull-left">
                            <div class="col">
                    
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="font-size: 14px; background-color: coral"><font color="white"><div id="txt"></div></font></a>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="font-size: 14px; background-color: coral"><font color="white"><div id="txt2"></div></font></a>

                            </div>
                        </span>
            <?php endif; ?>

                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <div class="col" style="text-align: right;">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="color: white"><?php echo e(auth()->user()->name); ?>&nbsp&nbsp&nbsp&nbsp&nbsp</a><br>
                                    
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: white"><i class="fa fa-power-off" aria-hidden="true">&nbsp;&nbsp;Log-Out</i></a>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="color: white">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</a>
                                    <form id="logout-form" action="<?php echo e(url(config('adminlte.logout_url', 'auth/logout'))); ?>" method="POST" style="display: none;">
                                        <?php if(config('adminlte.logout_method')): ?>
                                            <?php echo e(method_field(config('adminlte.logout_method'))); ?>

                                        <?php endif; ?>
                                        <?php echo e(csrf_field()); ?>

                                    </form>
                                </div>
                            </li>
                        </ul>
                    </ul>
                </div>
                <?php if(config('adminlte.layout') == 'top-nav'): ?>
                </div>
                <?php endif; ?>
            </nav>
        </header>

        <?php if(config('adminlte.layout') != 'top-nav'): ?>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar" style="font-size: 13px;">

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" data-widget="tree">
                    <?php echo $__env->renderEach('adminlte::partials.menu-item', $adminlte->menu(), 'item'); ?>
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
        <?php endif; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php if(config('adminlte.layout') == 'top-nav'): ?>
            <div class="container">
            <?php endif; ?>

            <!-- Main content -->
            <section class="content">
                
                <?php echo $__env->yieldContent('content'); ?>
            </section>
            <!-- /.content -->
            <?php if(config('adminlte.layout') == 'top-nav'): ?>
            </div>
            <!-- /.container -->
            <?php endif; ?>
        </div>
        <!-- /.content-wrapper -->

    </div>
    <!-- ./wrapper -->
    <div class="se-pre-con"></div>
<?php $__env->stopSection(); ?>
<style>
    .no-js #loader { display: none;  }
            .js #loader { display: block; position: absolute; left: 100px; top: 0; }
            .se-pre-con {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: url(https://img.pikbest.com/png-images/20190918/cartoon-snail-loading-loading-gif-animation_2734139.png!bw340) center no-repeat #fff;
            }
</style>
<?php $__env->startSection('adminlte_js'); ?>
    <script src="<?php echo e(asset('vendor/adminlte/dist/js/adminlte.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/adminlte/vendor/vuejs/vue.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/adminlte/vendor/axios/axios.min.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('js'); ?>
    <?php echo $__env->yieldContent('js'); ?>
<?php $__env->stopSection(); ?>

<script>
    function formatDate(date) {
          var monthNames = [
          "January", "February", "Maret",
          "April", "Mei", "Juni", "July",
          "Agustus", "September", "Oktober",
          "November", "December"
          ];

          var day = date.getDate();
          var monthIndex = date.getMonth();
          var year = date.getFullYear();

          return day + ' ' + monthNames[monthIndex] + ' ' + year;
    }

    function startTime() {

      var today = new Date();
      var today2 = formatDate(new Date())
      var h = today.getHours();
      var m = today.getMinutes();
      var s = today.getSeconds();
      var open_month = "<?php echo $period ?>";
      m = checkTime(m);
      s = checkTime(s);                                    

      document.getElementById('txt').innerHTML =
      "<font color=black><b>"+ today2 + "</b> | <b>" + h + ":" + m + ":" + s + "</b></font>";
        //   document.getElementById('txt2').innerHTML = "Periode Aktif : " + "<b>" + open_month + "</b>";
        var t = setTimeout(startTime, 500);
        
        $(document).ready(function() {
                                        $(".se-pre-con").fadeOut("slow");
                                    });

    }

    function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
    }                   
                                      
</script>


<?php echo $__env->make('adminlte::master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>