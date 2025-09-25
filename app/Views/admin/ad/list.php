<?= $this->extend('theme/vendor') ?>


<?= $this->section('content')?>


<div class="user_content">
<div class="container">
<div class="row pt-2 pb-2">
<div class="col-sm-12">
<ol class="breadcrumb ml-3 mr-3">
<li class="breadcrumb-item"><a href="">Home /</a></li>
<li class="breadcrumb-item"><a href="#">Ad /</a></li>
<li class="breadcrumb-item active" aria-current="page"><?=$type?></li>
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
<th>File</th>
<th>Title</th>
<th>State</th>
<th>City</th>
<th>Gender</th>
<th>Status</th>
</tr>
<?php $sr=1; foreach($results as $result){?>
    <tr>
    <td><?=$sr++?></td>
    <td><?php
        if($result->file==''){
            ?>
            <img src='https://via.placeholder.com/70'>
            <?php
        }else{
            ?>
            <img src='<?=base_url()?>/public/uploads/<?=$result->file?>' style='width:70px;height:70px'>
            <?php
        }
    ?></td>
    <td><?=$result->title?></td>
    <td><?=$result->state_name?></td>
    <td><?=$result->city_name?></td>
    <td><?=$result->gender?></td>
    <td><?=$type?></td>
   
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