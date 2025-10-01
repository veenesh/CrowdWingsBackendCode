<?= $this->extend('theme/admin') ?>

<?= $this->section('content') ?>

<style>
    .chat-container {
        display: flex;
        border: 1px solid #ddd;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        height: 500px;
    }

    /* Sidebar - User List */
    .user-list {
        width: 30%;
        background: #f8f9fa;
        padding: 15px;
        overflow-y: auto;
        border-right: 1px solid #ddd;
    }

    .user-list h4 {
        margin-bottom: 15px;
        font-size: 18px;
        color: #333;
    }

    .user-list a {
        display: block;
        padding: 12px;
        text-decoration: none;
        background: #fff;
        margin-bottom: 5px;
        border-radius: 5px;
        font-weight: bold;
        color: #333;
        transition: 0.3s;
    }

    .user-list a:hover, .user-list a.active {
        background: #007bff;
        color: #fff;
    }

    /* Chat Box */
    .chat-box {
        width: 70%;
        padding: 15px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .chat-box h3 {
        font-size: 20px;
        color: #007bff;
        margin-bottom: 10px;
    }

    .message-container {
        flex-grow: 1;
        overflow-y: auto;
        padding: 10px;
        background: #f1f1f1;
        border-radius: 5px;
    }

    .message {
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 10px;
        max-width: 70%;
        font-size: 14px;
    }

    .admin {
        background: #007bff;
        color: white;
        text-align: right;
        margin-left: auto;
    }

    .user {
        background: #e9ecef;
        color: black;
        text-align: left;
    }

    .message small {
        display: block;
        margin-top: 5px;
        font-size: 12px;
        color: #666;
    }

    /* Chat Form */
    .chat-form {
        display: flex;
        margin-top: 10px;
    }

    .chat-form textarea {
        flex-grow: 1;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        resize: none;
    }

    .chat-form button {
        background: #007bff;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        margin-left: 10px;
    }

    .chat-form button:hover {
        background: #0056b3;
    }
</style>

<div class="chat-container">
    <!-- Left Sidebar - User List -->
    <div class="user-list">
        <h4>Users</h4>
        <?php foreach ($users as $user): ?>
            <a href="?userId=<?= $user['user_id'] ?>" 
               class="<?= ($userId == $user['user_id']) ? 'active' : '' ?>">
                <?= esc($user['name']) ?> <!-- Display user's name instead of ID -->
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Right Side - Chat Window -->
    <div class="chat-box">
        <?php if ($userId): ?>
            <h3>Chat with <?= esc($users[array_search($userId, array_column($users, 'user_id'))]['name']) ?></h3>

            <div class="message-container">
                <?php foreach ($messages as $msg): ?>
                    <div class="message <?= $msg['message_from'] == 0 ? 'admin' : 'user' ?>">
                        <strong><?= $msg['message_from'] == 0 ? 'Admin' : 'User' ?>:</strong>
                        <?= esc($msg['message']) ?> <br>
                        <small><?= $msg['created_on'] ?></small>
                    </div>
                <?php endforeach; ?>
            </div>

            <form method="post" class="chat-form">
                <input type="hidden" name="message_to" value="<?= $userId ?>">
                <textarea name="message" required placeholder="Type a message..."></textarea>
                <button type="submit" name="submit">Send</button>
            </form>
        <?php else: ?>
            <p>Select a user from the left to start a chat.</p>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
