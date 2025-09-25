<?php $session=session();?>
<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>
    <!-- Meta tags  -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />

    <title>User Area : <?= COMPANY_NAME ?></title>
    <link rel="icon" type="image/png" href="<?= base_url() ?>/public/kasper/images/favicon.png" />

    <!-- CSS Assets -->
    <link rel="stylesheet" href="<?= base_url() ?>/public/kasper/css/app.css" />

    <!-- Javascript Assets -->
    <script src="<?= base_url() ?>/public/kasper/js/app.js" defer></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;display=swap" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script>
        /**
         * THIS SCRIPT REQUIRED FOR PREVENT FLICKERING IN SOME BROWSERS
         */
        localStorage.getItem("_x_darkMode_on") === "true" &&
            document.documentElement.classList.add("dark");
    </script>
    <style>
        .inputicon{width: 18px;}
        .help-block {
    color: red;
    display: block;
}
    </style>
</head>

<body x-data class="is-header-blur" x-bind="$store.global.documentBody">
    <!-- App preloader-->
    <div class="app-preloader fixed z-50 grid h-full w-full place-content-center bg-slate-50 dark:bg-navy-900">
        <div class="app-preloader-inner relative inline-block h-48 w-48"></div>
    </div>

    <!-- Page Wrapper -->
    <div id="root" class="min-h-100vh flex grow bg-slate-50 dark:bg-navy-900" x-cloak>
        <div class="fixed top-0 hidden p-6 lg:block lg:px-12">
            <a href="#" class="flex items-center space-x-2">
                <img class="h-12 w-12" src="<?= base_url() ?>/public/logo.png" alt="logo" />
                <p class="text-xl font-semibold uppercase text-slate-700 dark:text-navy-100">
                    <?=COMPANY_NAME?>
                </p>
            </a>
        </div>
        <div class="hidden w-full place-items-center lg:grid">
            <div class="w-full max-w-lg p-6">
                <img class="w-full" x-show="!$store.global.isDarkModeEnabled" src="<?= base_url() ?>/public/kasper/images/illustrations/dashboard-check.svg" alt="image" />
                <img class="w-full" x-show="$store.global.isDarkModeEnabled" src="images/illustrations/dashboard-check-dark.svg" alt="image" />
            </div>
        </div>
        <main class="flex w-full flex-col items-center bg-white dark:bg-navy-700 lg:max-w-md">
            <?php if ($session->has('warning')) : ?>
                <div class="alert alert-warning"><?= $session->getFlashdata('warning') ?></div>
            <?php endif; ?>
            <?php if ($session->has('error')) : ?>
                <div class="alert alert-danger"><?= $session->getFlashdata('error') ?></div>
            <?php endif; ?>
            <?php if ($session->has('success')) : ?>
                <div class="alert alert-success"><?= $session->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?= $this->renderSection('content') ?>

            <div class="my-5 flex justify-center text-xs text-slate-400 dark:text-navy-300">
                <a href="#">Privacy Notice</a>
                <div class="mx-3 my-1 w-px bg-slate-200 dark:bg-navy-500"></div>
                <a href="#">Term of service</a>
            </div>
        </main>
    </div>

    <!-- 
        This is a place for Alpine.js Teleport feature 
        @see https://alpinejs.dev/directives/teleport
      -->
    <div id="x-teleport-target"></div>
    <script>
        window.addEventListener("DOMContentLoaded", () => Alpine.start());
    </script>
</body>

</html>