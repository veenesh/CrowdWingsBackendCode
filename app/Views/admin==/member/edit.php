<?= $this->extend('theme/admin') ?>


<?= $this->section('content') ?>

<div class="tab-content current">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="demo_detail_section">
                <div class="detail_welcome_section">
                    <h3 class="welcome_heading">Member ID : <?=$user['member_id']?></h3>
                    <p><?=$user['name']?></p>
                </div>
            </div>
        </div>



    </div>
    <div class="row small">
        <div class="col-4">
            <div class="col-12">
                <div class="demo_detail_section">
                    <div class="">
                        <div class="inner_side_content">
                            <div class="" style="color: #fff;">
                                <p>MY ASSETS</p>
                                <h5 style="color: #fff;">$<?= $income['totalIncome'] ?></h5>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="demo_detail_section">
                    <div class="">
                        <div class="inner_side_content">
                            <div class="" style="color: #fff;">
                                <p>WALLET</p>
                                <h5 style="color: #fff;">$<?= $income['wallet_balance'] ?></h5>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="demo_detail_section">
                    <div class="">
                        <div class="inner_side_content">
                            <div class="" style="color: #fff;">
                                <p>TOTAL BUSINESS</p>
                                <h5 style="color: #fff;"><?= $income['totalBusiness'] ?></h5>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="demo_detail_section">
                    <p>Transfer Fund</p>
                    <form method="post">
                        <input type="text" class="form-control" name="wallet" placeholder="Wallet Address"><br />
                        <input type="number" class="form-control" name="amount" placeholder="Amount transfer"><br />
                        <input type="submit" name="withdrawal" value="Withdrawal" class="btn btn-primary">
                    </form>
                </div>
                
                
                <div class="demo_detail_section">
                    <p>Update</p>
                    <form method="post">
                        <input type="text" class="form-control" name="email" value="<?=$user['email']?>"><br />
                       
                        <input type="submit" name="update_email" value="Update Email" class="btn btn-primary">
                    </form>
                </div>
                
                <?php
                if($user['status']){
                    ?>
                     <form method="post">
                        <input type="submit" name="deactivate" value="Deactivate" class="btn btn-danger">
                    </form>
                    <?php
                }
                
                 if(!$user['status']){
                    ?>
                     <form method="post">
                        <input type="submit" name="activate" value="Activate" class="btn btn-success">
                    </form>
                    <?php
                }
                ?>
                
            </div>

        </div>
        <div class="col-8">

            <div class="row">

                <div routerlink="/income/direct" class="col-6" tabindex="0" ng-reflect-router-link="/income/direct">
                    <div class="card">
                        <div class="card-body text-center pb-1">
                            <div class="row">
                                <div class="col pr-0 text-left">
                                    <p class="text-uppercase font-weight-bold text-primary">Wallet USDT</p>
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="dynamicsparkline my-3"></div>
                                <h2 class="font-weight-light mb-0">$<?= $usdt ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div routerlink="/income/level" class="col-6" tabindex="0" ng-reflect-router-link="/income/level">
                    <div class="card">
                        <div class="card-body text-center pb-1">
                            <div class="row">
                                <div class="col pr-0 text-left">
                                    <p class="text-uppercase font-weight-bold text-primary">WALLET TRONs</p>
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="dynamicsparkline my-3"></div>
                                <h2 class="font-weight-light mb-0"><?= $tron ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div routerlink="/income/direct" class="col-6" tabindex="0" ng-reflect-router-link="/income/direct">
                    <div class="card">
                        <div class="card-body text-center pb-1">
                            <div class="row">
                                <div class="col pr-0 text-left">
                                    <p class="text-uppercase font-weight-bold text-primary"><i aria-hidden="true" class="fa fa-usd"></i> Introduce Income</p>
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="dynamicsparkline my-3"></div>
                                <h2 class="font-weight-light mb-0">$<?= $income['directIncome'] ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div routerlink="/income/level" class="col-6" tabindex="0" ng-reflect-router-link="/income/level">
                    <div class="card">
                        <div class="card-body text-center pb-1">
                            <div class="row">
                                <div class="col pr-0 text-left">
                                    <p class="text-uppercase font-weight-bold text-primary"><i aria-hidden="true" class="fa fa-usd"></i> Profit Share Income</p>
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="dynamicsparkline my-3"></div>
                                <h2 class="font-weight-light mb-0">$<?= $income['levelIncome'] ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-3"></div>
                <div routerlink="/income/roi" class="col-6" tabindex="0" ng-reflect-router-link="/income/roi">
                    <div class="card">
                        <div class="card-body text-center pb-1">
                            <div class="row">
                                <div class="col pr-0 text-left">
                                    <p class="text-uppercase font-weight-bold text-primary"><i aria-hidden="true" class="fa fa-usd"></i> Daily Income</p>
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="dynamicsparkline my-3"></div>
                                <h2 class="font-weight-light mb-0">$<?= $income['dailyIncome'] ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div routerlink="/income/reward" class="col-6" tabindex="0" ng-reflect-router-link="/income/reward">
                    <div class="card">
                        <div class="card-body text-center pb-1">
                            <div class="row">
                                <div class="col pr-0 text-left">
                                    <p class="text-uppercase font-weight-bold text-primary"><i aria-hidden="true" class="fa fa-usd"></i> Reward Income</p>
                                </div>
                            </div>
                            <div class="w-100">
                                <div class="dynamicsparkline my-3"></div>
                                <h2 class="font-weight-light mb-0">$<?= $income['rewardIncome'] ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>