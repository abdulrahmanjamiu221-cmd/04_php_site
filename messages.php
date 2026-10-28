<?php
declare(strict_types=1);

session_start();

$pageTitle = 'Messages';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

if (!isset($_SESSION['messages_csrf_token']) || !is_string($_SESSION['messages_csrf_token'])) {
    $_SESSION['messages_csrf_token'] = bin2hex(random_bytes(32));
}

$loginError = '';
$readError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (
        !is_string($csrfToken)
        || !hash_equals($_SESSION['messages_csrf_token'], $csrfToken)
    ) {
        $loginError = 'Your form session expired. Please try again.';
    } elseif (($_POST['action'] ?? '') === 'logout') {
        unset($_SESSION['messages_authenticated']);
        session_regenerate_id(true);
        $_SESSION['messages_csrf_token'] = bin2hex(random_bytes(32));
    } else {
        $password = $_POST['password'] ?? '';
        if (is_string($password) && hash_equals($messagesPassword, $password)) {
            session_regenerate_id(true);
            $_SESSION['messages_authenticated'] = true;
        } else {
            $loginError = 'The password was incorrect. Please try again.';
        }
    }
}

$isAuthenticated = ($_SESSION['messages_authenticated'] ?? false) === true;
$messages = [];

if ($isAuthenticated) {
    $messagesFile = __DIR__ . '/storage/messages.jsonl';

    if (is_file($messagesFile)) {
        try {
            $lines = file($messagesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if ($lines === false) {
                throw new RuntimeException('Unable to read the messages file.');
            }

            foreach ($lines as $lineNumber => $line) {
                try {
                    $message = json_decode($line, true, 512, JSON_THROW_ON_ERROR);
                } catch (JsonException $exception) {
                    throw new UnexpectedValueException(
                        'Invalid JSON on line ' . ($lineNumber + 1) . '.',
                        0,
                        $exception
                    );
                }

                if (!is_array($message)) {
                    throw new UnexpectedValueException('Invalid message data on line ' . ($lineNumber + 1) . '.');
                }

                $messages[] = $message;
            }

            usort(
                $messages,
                static fn (array $first, array $second): int =>
                    strcmp((string) ($second['timestamp'] ?? ''), (string) ($first['timestamp'] ?? ''))
            );
        } catch (RuntimeException | UnexpectedValueException $exception) {
            error_log('Unable to load contact messages: ' . $exception->getMessage());
            $readError = 'Messages could not be loaded. Please check the storage file and try again.';
            $messages = [];
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <p class="eyebrow">Contact inbox</p>
        <h1>Submitted messages</h1>
        <p>Review messages sent through the site contact form.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (!$isAuthenticated): ?>
            <div class="messages-login">
                <h2>Sign in to view messages</h2>
                <?php if ($loginError !== ''): ?>
                    <p class="form-alert form-alert-error" role="alert"><?= e($loginError) ?></p>
                <?php endif; ?>
                <form class="contact-form" method="post" action="messages.php">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['messages_csrf_token']) ?>">
                    <div class="form-field">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required>
                    </div>
                    <button class="button" type="submit">View messages</button>
                </form>
            </div>
        <?php else: ?>
            <div class="messages-heading">
                <p class="messages-count">
                    <span class="count-badge"><?= count($messages) ?></span>
                    <?= count($messages) === 1 ? 'message' : 'messages' ?>
                </p>
                <form method="post" action="messages.php">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['messages_csrf_token']) ?>">
                    <input type="hidden" name="action" value="logout">
                    <button class="button button-secondary" type="submit">Sign out</button>
                </form>
            </div>

            <?php if ($readError !== ''): ?>
                <p class="form-alert form-alert-error" role="alert"><?= e($readError) ?></p>
            <?php elseif ($messages === []): ?>
                <p class="messages-empty">There are no submitted messages yet.</p>
            <?php else: ?>
                <div class="messages-table-wrap">
                    <table class="messages-table">
                        <thead>
                            <tr>
                                <th scope="col">Received</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Service</th>
                                <th scope="col">Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $message): ?>
                                <?php
                                $timestamp = is_string($message['timestamp'] ?? null) ? $message['timestamp'] : '';
                                $receivedAt = strtotime($timestamp);
                                ?>
                                <tr>
                                    <td><?= e($receivedAt === false ? $timestamp : date('Y-m-d H:i', $receivedAt)) ?></td>
                                    <td><?= e(is_string($message['name'] ?? null) ? $message['name'] : '') ?></td>
                                    <td><?= e(is_string($message['email'] ?? null) ? $message['email'] : '') ?></td>
                                    <td><?= e(is_string($message['phone'] ?? null) ? $message['phone'] : '') ?></td>
                                    <td><?= e(is_string($message['service'] ?? null) ? $message['service'] : '') ?></td>
                                    <td class="message-cell"><?= nl2br(e(is_string($message['message'] ?? null) ? $message['message'] : '')) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
