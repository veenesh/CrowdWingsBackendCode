<?= $this->extend('theme/admin') ?>


<?= $this->section('content') ?>
    <!-- Include EmojiOneArea CSS from CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/emojionearea/dist/emojionearea.min.css">

    <!-- Include jQuery (required by EmojiOneArea) from CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include EmojiOneArea JavaScript from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/emojionearea/dist/emojionearea.min.js"></script>
    <style>
        .emojionearea .emojionearea-picker.emojionearea-picker-position-top {
    margin-top: 0 !important;
    right: -14px;
}
    </style>
    <div class="row">
        <div class='col-md-12'>
            <form method='post'>
                <level>Notification</level>
                <input type='text' name='message' class='form-control' required id="emoji-input">
                <br />
                <input type='submit' name='submit' value='Send'>
            </form>
        </div>
    </div>
 <script>
        // Initialize EmojiOneArea
        $(document).ready(function () {
            $("#emoji-input").emojioneArea();
        });
    </script>
<?= $this->endSection() ?>