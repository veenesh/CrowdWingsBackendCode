<?= $this->extend('theme/admin') ?>


<?= $this->section('content') ?>

<div class="tab-content current">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="demo_detail_section">
                <div class="detail_welcome_section">
                    <h3 class="welcome_heading">Change Password</h3>
                </div>
            </div>
        </div>



    </div>
    <div class="row small">
        
        <div class="row small mt-4">
    <h4>Change Member Password</h4>
    <form method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="member_id" class="form-label">Member ID</label>
            <input type="text" name="member_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <input type="submit" name='submit' class="btn btn-warning" value="Change Password">
    </form>
</div>
    </div>

</div>

<?= $this->endSection() ?>