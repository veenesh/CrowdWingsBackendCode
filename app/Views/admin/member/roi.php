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
                    <li class="breadcrumb-item"><a href="#">ROI Percent /</a></li>
                    
                </ol>
            </div>
        </div>
        <div class="mb-3">
            <div class="user_card_body ">
                <div class="user_card_body">
                    
                    
                    <form method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="member_id" class="form-label">Date</label>
            <input type="date" name="date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">ROI</label>
            <input type="text" name="roi" class="form-control" required>
        </div>
        <input type="submit" name='submit' class="btn btn-warning" value="Save">
    </form>
    
    
                    <div class="">
                        <table class="" id="myTable">
    <thead> 
        <tr>
            <th>Sr No.</th>
            <th>Date</th>
            <th>Per</th>
            
        </tr>
    </thead>
    <tbody>
        <?php
        $sr = 1;
        $total = 0;
        foreach ($results as $result) {
            $result = (object)$result;
        
        ?>
            <tr>
                <td><?= $sr++ ?></td>
                <td><?= $result->date ?></td>
                <td><?= $result->per ?></td>
                
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