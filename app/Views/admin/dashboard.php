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
                        <h4>10</h4>
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
                        <p>Total Vendor</p>
                        <h4>6</h4>
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
                        <p>Active User</p>
                        <h4>4</h4>
                    </div>
                    <div class="data_detail_inner_icon">
                        <i class="fa fa-inr" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-12 col-sm-12">
        <div class="demo_detail_section" style="background: linear-gradient(#fa709a, #fa709a);">
            <div class="detail_welcome_section">
                <div class="inner_side_content">
                    <div class="data_detail_inner">
                        <p>Active Vendor</p>
                        <h4>3</h4>
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