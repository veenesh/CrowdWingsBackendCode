<?= $this->extend('theme/admin') ?>


<?= $this->section('content') ?>

<div class="tab-content current">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="demo_detail_section">
                <div class="detail_welcome_section">
                    <h3 class="welcome_heading">Member ID : <?=$user['member_id']?></h3>
                </div>
            </div>
        </div>



    </div>
    <div class="row small">
        
        <form action="" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?= esc($user['name']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone_no" class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="<?= esc($user['phone']) ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="phone_no" class="form-label">Wallet Address</label>
                <input type="text" name="user_wallet" class="form-control" value="<?= esc($user['user_wallet']) ?>" required>
            </div>
            <input type="submit" class="btn btn-primary" name='update' value="Update">
            <a href="<?= base_url('admin/member/list') ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

</div>

<?= $this->endSection() ?>