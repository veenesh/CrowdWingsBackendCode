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
    <th>Vendor</th>
    <th>Title</th>
    <th>Image</th>
    <th>State</th>
    <th>City</th>
    <th>Date</th>
    <th>Status</th>
</tr>
    <?php $sr=1; foreach($results as $result){
        $result = (object)$result;
        ?>
    <tr>
    <td><?=$sr++?></td>
    <td><?=$result->vendor_id?></td>
    <td><?=$result->file?></td>
    <td><?=$result->gender?></td>
    <td><?=$result->state_id?></td>
    <td><?=$result->city_id?></td>
    <td><?=$result->created_at?></td>
    <td>
        <?=$result->status?>
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