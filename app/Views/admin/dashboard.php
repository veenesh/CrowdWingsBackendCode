<?= $this->extend('theme/admin') ?>


<?= $this->section('content')?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="demo_detail_section">
            <div class="detail_welcome_section">
                <h3 class="welcome_heading">Welcome Back, Admin!</h3>
                <marquee behavior="scroll" direction="left" scrollamount="10" class="">WELCOME TO <?=COMPANY_NAME?></marquee>
            </div>
        </div>
    </div>

    
    
</div>

<div class="row">
    <div class="col-lg-6 col-md-12 col-sm-12">
        <div class="demo_detail_section" style="background: linear-gradient(to top, #e78000, #ffd26a);">
            <div class="detail_welcome_section">
                <div class="inner_side_content">
                    <div class="data_detail_inner">
                        <p>Total User</p>
                        <h4><?=$totalM?></h4>
                    </div> 
                    <div class="data_detail_inner_icon">
                        <i class="fa fa-inr" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 col-md-12 col-sm-12">
        <div class="demo_detail_section" style="background: linear-gradient(to top, #e78000, #ffd26a);">
            <div class="detail_welcome_section">
                <div class="inner_side_content">
                    <div class="data_detail_inner">
                        <p>Active User</p>
                        <h4><?=$totalActive?></h4>
                    </div> 
                    <div class="data_detail_inner_icon">
                        <i class="fa fa-inr" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 col-md-12 col-sm-12">
        <div class="demo_detail_section" style="background: linear-gradient(to top, #e78000, #ffd26a);">
            <div class="detail_welcome_section">
                <div class="inner_side_content">
                    <div class="data_detail_inner">
                        <p>Inactive User</p>
                        <h4><?=$totalInactive?></h4>
                    </div> 
                    <div class="data_detail_inner_icon">
                        <i class="fa fa-inr" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="col-lg-6 col-md-12 col-sm-12">
        <div class="demo_detail_section" style="background: linear-gradient(to top, #e78000, #ffd26a);">
            <div class="detail_welcome_section">
                <div class="inner_side_content">
                    <div class="data_detail_inner">
                        <p>Upgrade</p>
                        <h4><?=$up?></h4>
                    </div>
                    <div class="data_detail_inner_icon">
                        <i class="fa fa-thumb-tack" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 col-md-12 col-sm-12">
        <div class="demo_detail_section" style="background: linear-gradient(to top, #e78000, #ffd26a);">
            <div class="detail_welcome_section">
                <div class="inner_side_content">
                    <div class="data_detail_inner">
                        <p>Today Collection</p>
                        <h4><?=$today_up?></h4>
                    </div>
                    <div class="data_detail_inner_icon">
                        <i class="fa fa-thumb-tack" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 col-md-12 col-sm-12">
        <div class="demo_detail_section" style="background: linear-gradient(to top, #e78000, #ffd26a);">
            <div class="detail_welcome_section">
                <div class="inner_side_content">
                    <div class="data_detail_inner">
                        <p>Today Withdrawal</p>
                        <h4><?=$today_withdrawal?></h4>
                    </div>
                    <div class="data_detail_inner_icon">
                        <i class="fa fa-thumb-tack" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="col-lg-6 col-md-12 col-sm-12">
        <div class="demo_detail_section" style="background: linear-gradient(to top, #e78000, #ffd26a);">
            <div class="detail_welcome_section">
                <div class="inner_side_content">
                    <div class="data_detail_inner">
                        <p>Yesterday Collection</p>
                        <h4><?=$yesterday_up?></h4>
                    </div>
                    <div class="data_detail_inner_icon">
                        <i class="fa fa-thumb-tack" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 col-md-12 col-sm-12">
        <div class="demo_detail_section" style="background: linear-gradient(to top, #e78000, #ffd26a);">
            <div class="detail_welcome_section">
                <div class="inner_side_content">
                    <div class="data_detail_inner">
                        <p>Yesterday Withdrawal</p>
                        <h4><?=$yesterday_withdrawal?></h4>
                    </div>
                    <div class="data_detail_inner_icon">
                        <i class="fa fa-thumb-tack" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
<div class="row">
    <div class="col-lg-6 col-md-12 col-sm-12">
        <div class="demo_detail_section" style="background: linear-gradient(to top, #87c646, #29f499);">
            <div class="detail_welcome_section">
                <div class="inner_side_content">
                    <div class="data_detail_inner">
                        <p>Withdrawal</p>
                        <h4><?=$withdrawal+150?></h4>
                    </div>
                    <div class="data_detail_inner_icon">
                        <i class="fa fa-inr" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-sm-12">
        <form method="post">
        <?php 
            if($withdrawal_status==0){
                ?>
                <input type="submit" name="withdrawal_on" value="Make Withdrawal On" class="btn btn-danger">
                <?php
            }else{
                ?>
                <input type="submit" name="withdrawal_off" value="Make Withdrawal Off" class="btn btn-success">
                <?php
            }
        ?>
        </form>
        <br /><br /><br />
    </div>
</div>

<!--<div class="row">
    
    <div class='col-md-6'>
        <h3>Today Collections</h3>
        <table class="">
        <thead> 
            <tr>
                <th>Sr No.</th>
                <th>Member ID</th>
                <th>Amount</th>
                
            </tr>
        </thead>
        <tbody>
            <?php
            $sr = 1;
            $total = 0;
            foreach ($collections as $result) {
                $result = (object)$result;
                
            ?>
                <tr>
                    <td><?= $sr++ ?></td>
                    <td><?= $result->member_id ?></td>
                    <td><?= $result->value ?></td>
                   
                </tr>
            <?php } ?>
        </tbody>
        </table>
    </div>
    
    <div class='col-md-6'>
        <h3>Today Withdrawals</h3>
        <table class="">
        <thead> 
            <tr>
                <th>Sr No.</th>
                <th>Member ID</th>
                <th>Amount</th>
                
            </tr>
        </thead>
        <tbody>
            <?php
            $sr = 1;
            $total = 0;
            foreach ($withdrawals as $result) {
                $result = (object)$result;
                
            ?>
                <tr>
                    <td><?= $sr++ ?></td>
                    <td><?= $result->member_id ?></td>
                    <td><?= $result->amount ?></td>
                   
                </tr>
            <?php } ?>
        </tbody>
        </table>
    </div>
</div>-->

<div class="row">
    
    

    <div class="demo_detail_section" id="section_tema_detail_data">
        <div class="card_new_body">
            <div class="location_link">
                <h2>We're here to help you!</h2>
                <p>Ask a question or file a support ticket, manage request, report an issues. Our team support team will get back to you by email.</p>
                <div class="user_support">
                    <a href="#" class="user_anchor">Get Support Now</a>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection()?>