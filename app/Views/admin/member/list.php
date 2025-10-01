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

        <div class="mb-3">
            <div class="user_card_body ">
                <div class="user_card_body">
                    <div class="">
                        <table class="" id="myTable">
    <thead> 
        <tr>
            <th>Sr No.</th>
            <th>Member ID</th>
            <th>Password</th>
            <th>Name</th>
            <th>Upgrade</th>
            <th>Phone</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sr = 1;
        $total = 0;
        foreach ($results as $result) {
            $result = (object)$result;
            $ADMIN = new \App\Controllers\AdminArea\Admin();
            $usdt = $result->usdt;
            $total += $usdt;
        ?>
            <tr>
                <td><?= $sr++ ?></td>
                <td><?= $result->username ?></td>
                <td><?= $result->password ?></td>
                <td><?= $result->name ?></td>
                <td><?= $usdt ?></td>
                <td><?= $result->phone ?></td>
                <td><?= $result->created_at ?></td>
                <td>
                    <a href="/app/admin/member/edit?id=<?= $result->id ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a target="_blank" href="https://app.endexcapital.org/?member_id=<?= $result->member_id ?>&password=<?= $result->password ?>" class="btn btn-primary btn-sm">Login</a>
                </td>
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