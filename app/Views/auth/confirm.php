<?= $this->extend('theme/kasper/auth') ?>


<?= $this->section('content')?>


<div class="flex w-full max-w-sm grow flex-col justify-center p-5">
<div class="text-center">
        <img class="mx-auto h-16 w-16 lg:hidden" src="<?= base_url() ?>/public/logo.png" alt="logo" />
        <div class="mt-4">
            <h2 class="text-2xl font-semibold text-slate-600 dark:text-navy-100">
                <?=$title?>
            </h2>
            <p class="text-slate-400 dark:text-navy-300">
                <?=$message?>
            </p><br />
        </div>
    </div>

    <div class="d-flex align-items-center flex-wrap justify-content-between">
                            <p class="signup mt-4">Click on login button and put member id and password <a href="login"
                                    class="signuplink">Login </a>
                            </p>
                        </div>   
</div>

</div>
<?= $this->endSection()?>