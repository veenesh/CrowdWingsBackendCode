<?= $this->extend('theme/admin') ?>


<?= $this->section('content') ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Slider Images</h3>
        <a href="sliders/upload" class="btn btn-primary">+ Upload New</a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if (!empty($sliders)): ?>
        <div class="row">
            <?php foreach ($sliders as $slider): ?>
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm border-0">
                        <img src="<?= base_url('uploads/sliders/' . $slider['image']) ?>" 
                             class="card-img-top rounded-top" 
                             alt="Slider Image"
                             style="height: 180px; object-fit: cover;">
                        <div class="card-body text-center">
                            <form action="<?= base_url('admin/sliders/delete/' . $slider['id']) ?>" method="post" onsubmit="return confirm('Delete this image?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <p class="text-muted">No slider images uploaded yet.</p>
            <a href="<?= route_to('admin.sliders.upload') ?>" class="btn btn-outline-primary">Upload Now</a>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>