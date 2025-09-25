<?= $this->extend('theme/vendor') ?>


<?= $this->section('content')?>

<div class="user_content">
<div class="container pages">
<div class="row">
<div class="col-sm-12">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="">Home </a></li>
<li class="breadcrumb-item active" aria-current="page">Profile</li>
</ol>
</div>
</div>
<div class="user_main_card mb-3">
<div class="user_card_body">
<h5 class="user_card_title profile_edit"><a href="profile/edit-profile"><i class="fa fa-edit"></i></a></h5>
<div class="user_form_row">

<h6 class="profile_information_heading">Profile Information</h6>
<div class="row">
    
<div class="col-lg-6 col-sm-6 col-md-6 mb-2">
<label class="label_user_title">Name</label>
<div class="input-group ">
<input name="" type="text" maxlength="10" id="" value="<?=$user->name?>" class="form-control user_input_text" placeholder="Name" readonly="">
</div>
</div>
<div class="col-lg-6 col-sm-6 col-md-6 mb-2">
<label class="label_user_title">Mobile</label>
<div class="input-group ">
<input name="" type="text" maxlength="10" id="" value="<?=$user->phone?>" class="form-control user_input_text" placeholder="Mobile" readonly="">
</div>
</div>
<div class="col-lg-6 col-sm-6 col-md-6 mb-2">
<label class="label_user_title">Email</label>
<div class="input-group ">
<input name="" type="text" maxlength="10" id="" value="<?=$user->email?>" class="form-control user_input_text" placeholder="Email" readonly="">
</div>
</div>
<div class="col-lg-6 col-sm-6 col-md-6 mb-2">
<label class="label_user_title">Joining Date</label>
<div class="input-group ">
<input name="" type="text" maxlength="10" id="" value="<?=$user->created_at?>" class="form-control user_input_text" placeholder="2018-12-26 10:19:08" readonly="">
</div>
</div>
<div class="col-lg-6 col-sm-6 col-md-6 mb-2">
<label class="label_user_title">Status</label>
<div class="input-group ">
 <?php $status="Inactive";
 if($user->amount==2600){
    $status="Active";
 }
 ?>   
<input name="" type="text" maxlength="10" id="" value="<?=$status?>" class="form-control user_input_text" placeholder="Active" readonly="">
</div>
</div>
<div class="col-lg-6 col-sm-6 col-md-6 mb-2">
<label class="label_user_title">Active Date</label>
<div class="input-group ">

<input name="" type="text" maxlength="10" id="" value="" class="form-control user_input_text" placeholder="" readonly="">
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>


<?= $this->endSection()?>