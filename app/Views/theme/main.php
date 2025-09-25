<?php $session=session();?>
<?php $member='/member';?>
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
    
    <script
      src="https://code.jquery.com/jquery-3.6.0.min.js"
      integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
      crossorigin="anonymous"
    ></script>
    
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    
</head>


<body>
    <div class="wrapper">
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div><img src="<?=base_url()?>/public/logo.png"></div>
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
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"> <i class="fa fa-users" aria-hidden="true "></i></div>
                <div class="menu-title">Downline</div>
            </a>
            <ul>
                <li><a href="<?=base_url().$member?>/team/direct"><i class="bx bx-right-arrow-alt"></i>Direct</a></li>
                <li><a href="<?=base_url().$member?>/team/generation"><i class="bx bx-right-arrow-alt"></i>Generation</a></li>
                <li><a href="<?=base_url().$member?>/team/unitlevel"><i class="bx bx-right-arrow-alt"></i>Unilevel Entries</a></li>
            </ul>
        </li>
        
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"> <i class="fa fa-thumb-tack " aria-hidden="true"></i></div>
                <div class="menu-title"> TOPUP</div>
            </a>
            <ul>
                <li><a href="<?=base_url().$member?>/topup"><i class="bx bx-right-arrow-alt"></i>Wallet</a></li>
                <li><a href="<?=base_url().$member?>/topup/income"><i class="bx bx-right-arrow-alt"></i>Fund</a></li>
            </ul>
        </li>

        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"> <i class="fa fa-thumb-tack " aria-hidden="true"></i></div>
                <div class="menu-title"> Buy ULP Position</div>
            </a>
            <ul>
                <li><a href="<?=base_url().$member?>/position"><i class="bx bx-right-arrow-alt"></i>Buy with USDT</a></li>
                <li><a href="<?=base_url().$member?>/position/income"><i class="bx bx-right-arrow-alt"></i>Buy from Income</a></li>
            </ul>
        </li>

        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"> <i class="fa fa-money " aria-hidden="true"></i></div>
                <div class="menu-title"> Fund</div>
            </a>
            <ul>
                <li><a href="<?=base_url().$member?>/fund/transfer"><i class="bx bx-right-arrow-alt"></i>Transfer Fund</a></li>
                <li><a href="<?=base_url().$member?>/fund/transfer/report"><i class="bx bx-right-arrow-alt"></i>Withdrawal History</a></li>
                <li><a href="<?=base_url().$member?>/fund/transfer/received"><i class="bx bx-right-arrow-alt"></i>Credit History</a></li>
            </ul>
        </li>

        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"> <i class="fa fa-usd " aria-hidden="true"></i></div>
                <div class="menu-title">Income Reports</div>
            </a>
            <ul>
                <li><a href="<?=base_url().$member?>/income/direct-income"><i class="bx bx-right-arrow-alt"></i>Direct Sponor Bonus</a></li>
                <li><a href="<?=base_url().$member?>/income/level-income"><i class="bx bx-right-arrow-alt"></i>Level Bonus</a></li>
                <li><a href="<?=base_url().$member?>/income/auto-pool-income"><i class="bx bx-right-arrow-alt"></i>Unilevel AutoPool Bonus-</a></li>
                <li><a href="<?=base_url().$member?>/income/gen-gap-income"><i class="bx bx-right-arrow-alt"></i>Generation Gap Bonus</a></li>
                <li><a href="<?=base_url().$member?>/income/global-leader-income"><i class="bx bx-right-arrow-alt"></i>Global Leadership Bonus</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"> <i class="fa fa-users" aria-hidden="true "></i></div>
                <div class="menu-title">Withdrawal</div>
            </a>
            <ul>
                <li><a href="<?=base_url().$member?>/withdrawal/request"><i class="bx bx-right-arrow-alt"></i>Withdrawal</a></li>
                <li><a href="<?=base_url().$member?>/withdrawal/report"><i class="bx bx-right-arrow-alt"></i>Withdrawal Report</a></li>
            </ul>
        </li>

   
        <li>
            <a href="avascript:;" class="has-arrow">
                <div class="parent-icon"><i class="fa fa-gear"></i></div>
                <div class="menu-title">My Account</div>
            </a>
            <ul>
                <li><a href="<?=base_url().$member?>/profile"><i class="bx bx-right-arrow-alt"></i>Profile</a></li>
                <li><a href="<?=base_url().$member?>/profile/change-password"><i class="bx bx-right-arrow-alt"></i>Change Password</a></li>
            </ul>
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
                        <?php if($session->get('status')=='inactive'){?>
                        <img src="<?=base_url()?>/public/theme/images/user.png" class="user-img" alt="user avatar" style="width:40px;height:40px">
                        <?php }?>
                        <?php if($session->get('status')=='active'){?>
                        <img src="<?=base_url()?>/public/theme/images/usergreen.png" class="user-img" alt="user avatar" style="width:40px;height:40px">
                        <?php }?>
                        <div class="user-info ps-3">
                            <p class="user-name mb-0"><?=$session->get('name')?> (<?=$session->get('memid')?>)</p>
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
    <?php if(isset($validation)): 
        print_r($validation->listErrors());
    endif; ?> 
        <?=$this->renderSection('content')?>
    </div>
</div>
<footer class="page-footer">
    <p class="mb-0">Copyright © 2022 <?=COMPANY_NAME?>. All right reserved.</p>
</footer>


<script src="<?=base_url()?>/public/theme/jquery.uploadifive.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>/public/theme/uploadifive.css">
<script src="<?=base_url()?>/public/theme/plugins/metismenu/js/metisMenu.min.js"></script>

<script src="<?=base_url()?>/public/theme/js/app.js"></script>

<?=$this->renderSection('script')?>

</body>
</html>