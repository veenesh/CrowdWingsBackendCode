<?= $this->extend('theme/kasper/auth') ?>


<?= $this->section('content')?>


<div class="flex w-full max-w-sm grow flex-col justify-center p-5">
<div class="text-center">
        <img class="mx-auto h-16 w-16 lg:hidden" src="<?= base_url() ?>/public/logo.png" alt="logo" />
        <div class="mt-4">
            <h2 class="text-2xl font-semibold text-slate-600 dark:text-navy-100">
                THANKYOU
            </h2>
            <p class="text-slate-400 dark:text-navy-300">
                You registered successfully
            </p><br />
        </div>
    </div>
<div class="persona_detail_data">
    <p>Please note your details at a safe place you will not see this page once page is refreshed.</p>
    <table style="width:100%">
        <tr>
            <td>Member ID</td>
            <td><?=$username?></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><?=$password?></td>
        </tr>
        <tr>
            <td>Your name</td>
            <td><?=$name?></td>
        </tr>
    </table>
    <div class="d-flex align-items-center flex-wrap justify-content-between">
                            <p class="signup mt-4">Click on login button and put member id and password <a href="login"
                                    class="signuplink">Login </a>
                            </p>
                        </div>   
</div>

</div>
<?= $this->endSection()?>