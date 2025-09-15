<?= $this->extend('theme/vendor') ?>


<?= $this->section('content')?>


<style>
 button.close {
    float: right;
}   
    
    
</style>
<div class="user_content">
<div class="container pages">
<div class="row pt-2 pb-2">
<div class="col-sm-12">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="<?=base_url()?>">Home </a></li>
<li class="breadcrumb-item active" aria-current="page">Edit-Account</li>
</ol>
</div>
</div>
<div class="user_main_card mb-3 ">
<div class="user_card_body user_content_page ">
<h5 class="user_card_title filter_title"><i class="fa fa-user-circle" aria-hidden="true"></i>USER DETAIL</h5>
</div>
<div class="user_card_body user_content_page ">
<form id="" action="" method="post" enctype="multipart/form-data">
<div class="user_form_row user_form_content">
<div class="row">

    <div class="col-lg-12 mb-3">
    <label class="label_user_title">Name</label>
    <div class="input-group ">
    <input name="name" type="text" id="" value="<?=$user->name?>" class="form-control user_input_text" placeholder="Name">
    </div>
    </div>



    <div class="col-lg-12 mb-3">
    <label class="label_user_title">Email</label>
    <div class="input-group ">
    <input name="email" type="text" id="" value="<?=$user->email?>" class="form-control user_input_text" placeholder="your_mail1@gmail.com">
    </div>
    </div>

    <div class="col-lg-12 mb-3">
    <label class="label_user_title">Mobile</label>
    <div class="input-group ">
    <input name="mobile" type="text" id="" value="<?=$user->phone?>" class="form-control user_input_text" placeholder="1234567890">
    </div>
    </div>

    <div class="col-lg-12 mb-3">
    <button type="submit" name='update_profile' data-response_area="action_areap" class="user_btn_button btn btn-primary">Update Profile</button>
    </div>
</div>
</div>


</form>

</div>
</div>
</div>
</div>


<?= $this->endSection()?>