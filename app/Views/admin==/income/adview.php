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
<th>Member ID</th>
<th>Income</th>
<th>Type</th>
<th>Date</th>
<th>Status</th>
</tr>
    <?php $sr=1; foreach($results as $result){
        $result = (object)$result;
        ?>
    <tr>
    <td><?=$sr++?></td>
    <td><?=$result->member_id?></td>
    <td><?=$result->income?></td>
    <td><?=$result->type?></td>
    <td><?=$result->date?></td>
    <td>
        <form method="POST">
            <input type="hidden" name="id" value="<?=$result->id?>">
            <input type='submit' name='delete' value='Delete' class='btn btn-danger btn-sm'>
        </form>
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