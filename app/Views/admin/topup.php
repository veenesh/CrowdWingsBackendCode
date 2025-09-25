<?= $this->extend('theme/admin') ?>


<?= $this->section('content')?>
<style>
    .topup form {
        background: #0b0c26;
        padding: 2em;
        color: #fff;
    }
</style>
<div class='row'>

    <div class='col-md-4'></div>
    <div class='col-md-4 topup'>
        <form method="POST">
            <label>Username</label>
            <input type='text' name='username' class='form-control' required>

            <label>Product</label>
            <select name='product' class='form-control' required>
                <option value=''>Select Product</option>
                <?php foreach($products as $product){
                    $product=(object)$product;
                    ?>
                    <option value='<?=$product->id?>'><?=$product->name?></option>
                    <?php 
                }?>
            </select>
                <br />
            <input type='submit' name='add_order' value='Add Order' class='btn btn-primary'>
        </form>
    </div>
</div>


<?= $this->endSection()?>