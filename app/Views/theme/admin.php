<?php $session=session();?>
<?php $member='/admin';?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="CPY" type="image/png" />
    <link href="<?=base_url()?>/public/theme/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <link href="<?=base_url()?>/public/theme/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="<?=base_url()?>/public/theme/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="<?=base_url()?>/public/theme/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href=" <?=base_url()?>/public/theme/css/pace.min.css" rel="stylesheet" />
    <script src="<?=base_url()?>/public/theme/js/pace.min.js"></script>
    <link href="<?=base_url()?>/public/theme/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url()?>/public/theme/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="<?=base_url()?>/public/theme/css/app.css" rel="stylesheet">
    <link href="<?=base_url()?>/public/theme/css/icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=base_url()?>/public/theme/css/custom.css" />
    <link rel="stylesheet" href="<?=base_url()?>/public/theme/css/style1.css" />
    <link rel="stylesheet" href="<?=base_url()?>/public/theme/css/dark-theme.css" />
    <link rel="stylesheet" href="<?=base_url()?>/public/theme/css/semi-dark.css" />
    <link rel="stylesheet" href="<?=base_url()?>/public/theme/css/header-colors.css" />
    <title>Welcome to <?=COMPANY_NAME?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
            <div><img src="<?=base_url()?>/public/front/images/logo.png"><?=COMPANY_NAME?></div>
            <div>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i></div>
    </div>

    <ul class="metismenu" id="menu">
        <li>
            <a href="<?=base_url().$member?>/dashboard" class="">
                <div class="parent-icon"><i class='bx bx-home-circle'></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>

        <li>
            <a href="<?=base_url().$member?>/member/list?type=all" class="">
                <div class="parent-icon"><i class="fa fa-gear"></i></div>
                <div class="menu-title">Members</div>
            </a>
        </li>

        
       <li>
            <a href="<?=base_url().$member?>/member/withdrawal" class="">
                <div class="parent-icon"><i class="fa fa-usd"></i></div>
                <div class="menu-title">Withdrawal</div>
            </a>
        </li>
    
       
        <li>
            <a href="<?=base_url().$member?>/logout">
                <div class="parent-icon"> <i class="fa fa-sign-out new_left primary_color_page" aria-hidden="true"></i></div>
                <div class="menu-title">Logout</div>
            </a>
        </li>
    </ul>
<header>
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand">
            <div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>
                <div class="ghost1 ">
                    <b>Server Time: <?=date('d-m-Y H:i:s')?></b>        
                </div>
                <div class="user-box dropdown">
                        <img src="<?=base_url()?>/public/theme/images/user.png" class="user-img" alt="user avatar" style="width:40px;height:40px">
                        <div class="user-info ps-3">
                            <p class="user-name mb-0"><?=$session->get('name')?></p>
                            <p class="designattion mb-0"><?=$session->get('email')?></p>
                        </div>
                </div>
            </div>
        </nav>
    </div>
</header>

<div class="page-wrapper">
    <div class="page-content">
    <?php if($session->has('warning')):?>
        <div class="alert alert-warning"><?=$session->getFlashdata('warning')?></div>
    <?php endif;?> 
    <?php if($session->has('error')):?>
        <div class="alert alert-danger"><?=$session->getFlashdata('error')?></div>
    <?php endif;?>    
    <?php if($session->has('success')):?>
        <div class="alert alert-success"><?=$session->getFlashdata('success')?></div>
    <?php endif;?> 
        <?=$this->renderSection('content')?>
    </div>
</div>
<footer class="page-footer">
    <p class="mb-0">Copyright © 2022 <?=COMPANY_NAME?>. All right reserved.</p>
</footer>



<script src="<?=base_url()?>/public/theme/plugins/simplebar/js/simplebar.min.js"></script>
<script src="<?=base_url()?>/public/theme/plugins/metismenu/js/metisMenu.min.js"></script>
<script src="<?=base_url()?>/public/theme/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
<script src="<?=base_url()?>/public/theme/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
<script src="<?=base_url()?>/public/theme/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="<?=base_url()?>/public/theme/plugins/chartjs/js/Chart.min.js"></script>
<script src="<?=base_url()?>/public/theme/plugins/chartjs/js/Chart.extension.js"></script>
<script src="<?=base_url()?>/public/theme/js/index.js"></script>

<script src="<?=base_url()?>/public/theme/js/app.js"></script>
<script src="<?=base_url()?>/public/theme/css/js/script.js"></script>

</body>
</html>