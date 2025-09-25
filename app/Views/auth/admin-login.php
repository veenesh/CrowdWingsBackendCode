<?= $this->extend('theme/auth') ?>


<?= $this->section('content')?>

<style>
    body{background:#ca2b2b;}
    .registerLogin {
    border: 1px solid #c86161;
    padding: 20px;
    background: #b22a2a;
    max-width: 700px;
    margin: auto;
    margin-top: 5em;
    color: #fff;
}
</style>
<div class="welcome_inner_content">
    <h4>Login</h4>
    <p>Welcome back, login to continue</p>
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
                            
                        </div>
                    </form>
</div>


<?= $this->endSection()?>