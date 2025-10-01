<?= $this->extend('theme/admin') ?>


<?= $this->section('content')?>


<div class="user_content">
<div class="container">
<div class="row pt-2 pb-2">
<div class="col-sm-12">
<ol class="breadcrumb ml-3 mr-3">
<li class="breadcrumb-item"><a href="#">Home /</a></li>
<li class="breadcrumb-item"><a href="#"><?=$title?> /</a></li>
<li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
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
<th>Name</th>
<th>Bank Details</th>
<th>Amount</th>
<th>Admin charge</th>
<th>TDS</th>
<th>Net Amount</th>
<th>Date</th>
<th>Status</th>
</tr>
    <?php $sr=1; foreach($results as $result){
        $result = (object)$result;
        ?>
    <tr>
    <td><?=$sr++?></td>
    <td><?=$result->name?></td>
    <td><?=$result->bank?><br /><?=$result->account_no?></td>
    <td><?=$result->amount?></td>
    <td><?=$result->admin_charge?></td>
    <td><?=$result->tds?></td>
    <td><?=$result->amount_after_deduction?></td>
    <td><?=$result->date?></td>
    <td>
        <?php 
        if($result->status==0){
            echo "Pending";
            ?>
            <form method="post">
                <input type="hidden" name='id' value='<?=$result->id?>'>
                <input type="submit" name="approve" value='Approve' class="btn btn-primary btn-sm">
                <input type="submit" name="reject" value='Reject' class="btn btn-danger btn-sm">
            </form>
            <?php
        }
        if($result->status==1){
            echo "Successfull";
            
        }
        if($result->status==2){
            echo "Rejected";
            
        }
        ?>
     
    </td>
    </tr>
    <?php }?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>

<?= $this->endSection()?>