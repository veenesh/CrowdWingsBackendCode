<?php 
$session=session();
$member='/public/member';?>
<!doctype html>
<html lang="en">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="icon" href="CPY" type="image/png" />

<title>User Area : Home Safe</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="<?=base_url()?>/public/theme/css/bootstrap.min.css" rel="stylesheet">
<link href="<?=base_url()?>/public/theme/css/auth.css" rel="stylesheet">
</head>
<body>
<style>


</style>
<div class="wrapper">
    <div class='registerLogin' style='background:#7b0c09'>
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




</body>
</html>