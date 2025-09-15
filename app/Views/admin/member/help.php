<?= $this->extend('theme/admin') ?>


<?= $this->section('content') ?>

<style>
    tr.kyc_done {
        background: green;
    }
</style>
<div class="user_content">
    <div class="container">
        <div class="row pt-2 pb-2">
            <div class="col-sm-12">
                <ol class="breadcrumb ml-3 mr-3">
                    <li class="breadcrumb-item"><a href="#">Home /</a></li>
                    <li class="breadcrumb-item"><a href="#"><?= $title ?> /</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
                </ol>
            </div>
        </div>

        <div class="user_main_card mb-3">
            <div class="user_card_body ">
                <div class="user_card_body">
                    <div class="user_table_data">
                        <table class="user_table_info_record">
                            <tbody>
                                <tr>
                                    <th>Sr No.</th>
                                    <th>Member ID</th>
                                    <th>Wallet Address</th>
                                    <th>Password</th>
                                    <th>Name</th>
                                    <th>Balance</th>
                                    <th>Phone</th>
                                    <th>Date</th>
                                </tr>
                                <?php

                                                                            use App\Controllers\AdminArea\Admin;

 $sr = 1;
                                foreach ($results as $result) {
                                    $result = (object)$result;
                                    $ADMIN = new Admin();
                                    $walletB = $ADMIN->walletBalance($result->wallet_address);
                                    $tron = $walletB->tron;
                                    $usdt = $walletB->usdt;
                                ?>
                                    <tr>
                                        <td><?= $sr++ ?></td>
                                        <td><a href="edit?id=<?= $result->id ?>"><?= $result->username ?></a></td>
                                        <td><?= $result->wallet_address ?></td>
                                        <td><?= $result->password ?></td>
                                        <td><?= $result->name ?></td>
                                        <td>TRX : <?= $tron ?> USDT :<?= $usdt ?></td>
                                        <td><?= $result->phone ?></td>
                                        <td><?= $result->created_at ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>