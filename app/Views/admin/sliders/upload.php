<?= $this->extend('theme/admin') ?>


<?= $this->section('content') ?>
<h2>Upload Slider Images</h2>

<?php if (session()->getFlashdata('success')): ?>
    <p style="color:green;"><?= session()->getFlashdata('success') ?></p>
<?php elseif (session()->getFlashdata('error')): ?>
    <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="file" name="images[]" multiple required>
    <button type="submit">Upload</button>
</form>
<?= $this->endSection() ?>