<?= $this->extend('theme/kasper/auth') ?>


<?= $this->section('content') ?>


<div class="flex w-full max-w-sm grow flex-col justify-center p-5">
    <div class="text-center">
        <img class="mx-auto h-16 w-16 lg:hidden" src="<?= base_url() ?>/public/logo.png" alt="logo" />
        <div class="mt-4">
            <h2 class="text-2xl font-semibold text-slate-600 dark:text-navy-100">
                Welcome To <?= COMPANY_NAME ?>
            </h2>
            <p class="text-slate-400 dark:text-navy-300">
                Please sign up to continue
            </p>
        </div>
    </div>
    <div class="mt-10">
        <form method="POST">
 
            <label>Sponsor ID <span class="sponsor_name" style="font-size: 11px; font-weight: bold;"></span></label>
            <label class="relative flex">
                <input class="sponsor_id form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" placeholder="Sponsor ID" name="sponsor_id" type="text" autofocus value="<?= set_value('sponsor_id') ?>" />
                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                    <i class="fa fa-user-o inputicon"></i>
                </span>
            </label>
            <?php
            if (isset($validation)) {
                if ($validation->hasError('sponsor_id')) {
                    echo $validation->showError('sponsor_id');
                }
            }
            ?>
            <label>Name</label>
            <label class="relative flex">
                <input class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" placeholder="Name" name="name" type="text" autofocus value="<?= set_value('name') ?>" />
                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                <i class="fa fa-user-o inputicon"></i>
                </span>
            </label>
            <?php
            if (isset($validation)) {
                if ($validation->hasError('name')) {
                    echo $validation->showError('name');
                }
            }
            ?>

            <label>Email</label>
            <label class="relative flex">
                <input class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" placeholder="Email ID" name="email" type="text"autofocus value="<?= set_value('email') ?>" />
                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                <i class="fa fa-envelope-o inputicon"></i>
                </span>
            </label>
            <?php
            if (isset($validation)) {
                if ($validation->hasError('email')) {
                    echo $validation->showError('email');
                }
            }
            ?>

            <label>Phone Number</label>
            <label class="relative flex">
                <input class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" placeholder="Phone" name="phone" type="text" autofocus value="<?= set_value('phone') ?>" />
                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                <i class="fa fa-phone inputicon"></i>
                </span>
            </label>
            <?php
            if (isset($validation)) {
                if ($validation->hasError('phone')) {
                    echo $validation->showError('phone');
                }
            }
            ?>
                        <label>Password</label>

            <label class="relative mt-4 flex">
                <input class="form-input peer w-full rounded-lg bg-slate-150 px-3 py-2 pl-9 ring-primary/50 placeholder:text-slate-400 hover:bg-slate-200 focus:ring dark:bg-navy-900/90 dark:ring-accent/50 dark:placeholder:text-navy-300 dark:hover:bg-navy-900 dark:focus:bg-navy-900" placeholder="Password" name="password" type="password" />
                <span class="pointer-events-none absolute flex h-full w-10 items-center justify-center text-slate-400 peer-focus:text-primary dark:text-navy-300 dark:peer-focus:text-accent">
                <i class="fa fa-lock inputicon"></i>
                </span>
            </label>
            <?php
            if (isset($validation)) {
                if ($validation->hasError('password')) {
                    echo $validation->showError('password');
                }
            }
            ?>

            <div class="mt-4 flex items-center justify-between space-x-2">
                
                <a href="https://kasper24.us/app/index.php/user/login" class="text-xs text-slate-400 transition-colors line-clamp-1 hover:text-slate-800 focus:text-slate-800 dark:text-navy-300 dark:hover:text-navy-100 dark:focus:text-navy-100">Sign In</a>
            </div>
            <button type="submit" name='register' class="btn mt-10 h-10 w-full bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                Sign Up
            </button>
   
 

        </form>
    </div>
</div>



<script>
    $(document).ready(function() {
        if (window.tronWeb && window.tronWeb.defaultAddress.base58) {
            $('.walletaddress').val(window.tronWeb.defaultAddress.base58);
            $("#wallet").html('<p class="mb-0 mt-2 fs-12 text-start"><span class="fa fa-dot-circle-o text-success"></span> Connected : <span id="lblWalletConnected">' + window.tronWeb.defaultAddress.base58 + '</span></p>');

        } else {
            $('.walletaddress').val('');
            $("#wallet").html('<p class="mb-0 mt-2 fs-12 text-start"><span class="fa fa-dot-circle-o text-danger"></span> Connected : <span id="lblWalletConnected">You are not connected to any wallet</span></p>');
        }
    });
    $('.sponsor_id').on('keyup', function() {
        var id = $(".sponsor_id").val();

        $.ajax({
            url: "<?= base_url() ?>/load/sponsor/" + id,
            type: 'get',
            success: function(response) {
                $(".sponsor_name").html(response);
            }

        });

    });
    $(".state").on("change", function() {
        var id = $(".state").val();
        $.ajax({
            url: "<?= base_url() ?>/load/city/" + id,
            type: 'get',
            success: function(response) {
                $(".city").html(response);
            }

        });
    });

    
</script>
<?= $this->endSection() ?>