<?= $this->extend('theme/kasper/auth') ?>


<?= $this->section('content') ?>

<div class="flex w-full max-w-sm grow flex-col justify-center p-5">
    <div class="text-center">
        <img class="mx-auto h-16 w-16 lg:hidden" src="<?= base_url() ?>/public/logo.png" alt="logo" />
        <div class="mt-4">
            <h2 class="text-2xl font-semibold text-slate-600 dark:text-navy-100">
                Welcome Back
            </h2>
            <p class="text-slate-400 dark:text-navy-300">
                Send password to your email
            </p>
        </div>
    </div>
    <div class="mt-16">
        <form method="POST">
            <label class="relative flex">
                
                <input class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" placeholder="Member ID" name="member_id" type="text" />
                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                <i class="fa fa-user-o inputicon"></i>
                </span>
            </label>

  
            <button type="submit" name='login' class="btn mt-10 h-10 w-full bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                Send Password
            </button>
            
            <div class="my-7 flex items-center space-x-3">
                <div class="h-px flex-1 bg-slate-200 dark:bg-navy-500"></div>
                <p>OR</p>
                <div class="h-px flex-1 bg-slate-200 dark:bg-navy-500"></div>
            </div>
<div class="mt-4 text-center text-xs+">
                <p class="line-clamp-1">
                    

                    <a class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent" href="login">Login</a>
                </p>
            </div>
        </form>
    </div>
</div>



<?= $this->endSection() ?>