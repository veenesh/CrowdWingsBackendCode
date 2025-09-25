<?= $this->extend('theme/auth-vendor') ?>


<?= $this->section('content')?>


<div class="welcome_inner_content">

    <a href="#"><img src="<?=base_url()?>/public/theme/images/user11.png" class="img-thumbnail" style="width:80px;height:80px"></a>
    <h4>Login</h4>
    <p>Welcome back vendor, login to continue</p>
</div>
<div class="persona_detail_data">
<form method="POST">
                        <div class="form-group">
                            <label for="exampleInputEmail1" class="input__label">User ID</label>
                            <input type="text" class="form-control login_text_field_bg input-style"
                                 placeholder="User ID" name="member_id" value="" required="" autofocus >
                        </div>  
                        <div class="form-group">  
                             <label for="exampleInputEmail1" class="input__label">Password</label>
                            <input type="password" class="form-control login_text_field_bg input-style"
                                 placeholder="Password" name="password" value="" required="" autofocus >
                        </div>
                        <div class="form-check check-remember check-me-out">
                            <input type="checkbox" class="form-check-input checkbox" id="exampleCheck1">
                            <label class="form-check-label checkmark" for="exampleCheck1">Remember
                                me</label>
                        </div>
                        <div class="d-flex align-items-center flex-wrap justify-content-between">
                            <input type="submit" class="btn btn-primary btn-style mt-4" name='login' value="Login now">
                            <p class="signup mt-4">Don’t have an account? <a href="register"
                                    class="signuplink">Sign
                                    up</a></p>
                        </div>
                    </form>
</div>


<?= $this->endSection()?>