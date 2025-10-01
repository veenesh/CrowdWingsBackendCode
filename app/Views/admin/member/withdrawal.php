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
                                    
                                   
                                    <th>Amount</th>
                                    <th>Transfer Amount</th>
                                    <th>Wallet Address</th>
                                    
                                    <th>Date</th>
                                    <th>Satus</th>
                                </tr>
                                <?php

                                                                            use App\Controllers\AdminArea\Admin;

 $sr = 1;
 $total=0;
                                foreach ($results as $result) {
                                    $result = (object)$result;
                                    $ADMIN = new Admin();
                                    //$walletB = $ADMIN->walletBalance($result->wallet_address);
                                    $tron = 0;
                                    $usdt = $result->usdt;
                                    $total+=$usdt;
                                ?>
                                    <tr>
                                        <td><?= $sr++ ?></td>
                                        <td><?= $result->member_id ?></td>
                                        
                                       
                                        <td><?= $result->amount ?></td>
                                        <td><?= $result->transfer_amount ?></td>
                                        <td><?= $result->upgrade_id ?></td>
                                        <td><?= $result->date_created ?></td>
                                        <td><?php 
                                            if($result->status==0){
                                                ?>
                                                <form method="post">
                                                    <input type="hidden" name="id" value="<?=$result->id?>">
                                                    <input type="submit" name="autotransfer" value="Auto Transfer" class="btn btn-secondary btn-sm">
                                                    <input type="submit" name="manuallytransfer" value="Manual Transfer" class="btn btn-info btn-sm">
                                                    <input type="submit" name="rejected" value="Reject" class="btn btn-danger btn-sm">
                                                </form>
                                                <?php
                                            }
                                            else if($result->status==1){echo "Auto Transfer";}
                                            else if($result->status==2){echo "Rejected";}
                                            else if($result->status==5){echo "Manually Transfer";}
                                        ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        
                        total = <?=$total?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>