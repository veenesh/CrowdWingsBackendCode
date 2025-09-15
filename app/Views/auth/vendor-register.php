<?= $this->extend('theme/auth-vendor') ?>


<?= $this->section('content')?>


<div class="welcome_inner_content">
    <h4>Register</h4>
    <p>Welcome back, login to continue</p>
</div>
<div class="persona_detail_data">
    <form method="POST">
                      
                        <div class="form-group">
                            <label for="exampleInputName" class="input__label">Your Name</label>
                            <input type="text" class="form-control login_text_field_bg input-style"
                                name='name'  value="<?=set_value('name')?>">
                                <?php
									 if(isset($validation)){
										 if($validation->hasError('name')){
											 echo $validation->showError('name');
										 }
									 }
								 ?>    
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1" class="input__label">Email address</label>
                            <input type="text" class="form-control login_text_field_bg input-style"
                                name='email'  value="<?=set_value('email')?>">

                                <?php
									 if(isset($validation)){
										 if($validation->hasError('email')){
											 echo $validation->showError('email');
										 }
									 }
								 ?>   
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1" class="input__label">Phone number</label>
                            <input type="text" class="form-control login_text_field_bg input-style"
                                name='phone'  value="<?=set_value('phone')?>">
                                <?php
									 if(isset($validation)){
										 if($validation->hasError('phone')){
											 echo $validation->showError('phone');
										 }
									 }
								 ?>
                                
                        </div>
               
                      
                        <div class="form-group">
                            <label for="exampleInputPassword1" class="input__label">State</label>
                            <select name='state' class='form-control state'>
                                <option value=''>Select State</option>
                                <?php foreach($states as $state){?>
                                    <option value='<?=$state['id']?>'  <?=set_select('state', $state['id'], False); ?>><?=$state['name']?></option>
                                <?php }?>    
                            </select>

                                <?php
									 if(isset($validation)){
										 if($validation->hasError('state')){
											 echo $validation->showError('state');
										 }
									 }
								 ?>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1" class="input__label">City</label>
                            <select name='city' class='form-control city'>
                                <option value=''>Select City</option>
                                <?php foreach($cities as $city){?>
                                    <option value='<?=$city['id']?>'  <?=set_select('city', $city['id'], False); ?>><?=$city['name']?></option>
                                <?php }?>
                            </select>

                                <?php
									 if(isset($validation)){
										 if($validation->hasError('city')){
											 echo $validation->showError('city');
										 }
									 }
								 ?>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1" class="input__label">Password</label>
                            <input type="password" class="form-control login_text_field_bg input-style"
                                name='password' >

                                <?php
									 if(isset($validation)){
										 if($validation->hasError('password')){
											 echo $validation->showError('password');
										 }
									 }
								 ?>
                        </div>
       
                        <div class="d-flex align-items-center flex-wrap justify-content-between">
                            <input type="submit" name="register" class="btn btn-primary btn-style mt-4" value="Create Account">
                            <p class="signup mt-4">Already have an account? <a href="login"
                                    class="signuplink">Login </a>
                            </p>
                        </div>                    
    </form>
</div>

<script>
    $('.sponsor_id').on('change', function(){
        var id=$(".sponsor_id").val();

        $.ajax({
					  url: "<?=base_url()?>/load/sponsor/"+id,
					  type: 'get',
					  success: function(response){
					  		$(".sponsor_name").val(response);
					  }
					   
		});

    });
    $( ".state" ).on( "change", function() {
		  var id=$(".state").val();
			$.ajax({
					  url: "<?=base_url()?>/load/city/"+id,
					  type: 'get',
					  success: function(response){
					  		$(".city").html(response);
					  }
					   
		});
	});
</script>
<?= $this->endSection()?>