<?php
declare(strict_types=1);

session_start();

$pageTitle = 'Contact';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/data.php';

if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$values = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'service' => '',
    'message' => '',
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (array_keys($values) as $field) {
        $submittedValue = $_POST[$field] ?? '';

        if (is_string($submittedValue)) {
            $values[$field] = trim($submittedValue);
        } else {
            $errors[$field] = 'Please enter a valid value.';
        }
    }

    $submittedToken = $_POST['csrf_token'] ?? '';
    if (
        !is_string($submittedToken)
        || !hash_equals($_SESSION['csrf_token'], $submittedToken)
    ) {
        $errors['_csrf'] = 'Your form session expired. Please review the form and submit it again.';
    }

    if ($values['name'] === '' && !isset($errors['name'])) {
        $errors['name'] = 'Please enter your name.';
    }

    if ($values['email'] === '' && !isset($errors['email'])) {
        $errors['email'] = 'Please enter your email address.';
    } elseif (
        !isset($errors['email'])
        && filter_var($values['email'], FILTER_VALIDATE_EMAIL) === false
    ) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    if ($values['service'] === '' && !isset($errors['service'])) {
        $errors['service'] = 'Please select a service.';
    } elseif (!isset($errors['service'])) {
        $serviceExists = false;
        foreach ($services as $service) {
            if ($service['slug'] === $values['service']) {
                $serviceExists = true;
                break;
            }
        }

        if (!$serviceExists) {
            $errors['service'] = 'Please select one of the listed services.';
        }
    }

    if (!isset($errors['message'])) {
        $messageLength = preg_match_all('/./us', $values['message'], $messageCharacters);
        if ($messageLength === false || $messageLength < 10 || $messageLength > 1000) {
            $errors['message'] = 'Your message must be between 10 and 1,000 characters.';
        }
    }

    if ($errors === []) {
        $selectedService = '';
        foreach ($services as $service) {
            if ($service['slug'] === $values['service']) {
                $selectedService = $service['title'];
                break;
            }
        }

        $submission = [
            'timestamp' => gmdate('c'),
            'name' => $values['name'],
            'email' => $values['email'],
            'phone' => $values['phone'],
            'service' => $selectedService,
            'message' => $values['message'],
        ];

        try {
            $jsonLine = json_encode($submission, JSON_THROW_ON_ERROR) . PHP_EOL;
            $storageDirectory = __DIR__ . '/storage';

            set_error_handler(
                static function (int $severity, string $message, string $file, int $line): never {
                    throw new ErrorException($message, 0, $severity, $file, $line);
                }
            );

            try {
                if (!is_dir($storageDirectory) && !mkdir($storageDirectory, 0750, true) && !is_dir($storageDirectory)) {
                    throw new RuntimeException('Unable to create the storage directory.');
                }

                $bytesWritten = file_put_contents(
                    $storageDirectory . '/messages.jsonl',
                    $jsonLine,
                    FILE_APPEND | LOCK_EX
                );
                if ($bytesWritten === false) {
                    throw new RuntimeException('Unable to write the contact submission.');
                }
            } finally {
                restore_error_handler();
            }

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header('Location: contact.php?sent=1');
            exit;
        } catch (JsonException | ErrorException | RuntimeException $exception) {
            error_log('Contact form submission failed: ' . $exception->getMessage());
            $errors['_form'] = 'We could not save your message right now. Please try again later.';
        }
    }
}

$messageSent = ($_GET['sent'] ?? null) === '1';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="container">
        <p class="eyebrow">Get in touch</p>
        <h1>Tell us what you’re working on.</h1>
        <p>Have a question or an idea? We’d be happy to hear from you.</p>
    </div>
</section>

<section class="section">
    <div class="container contact-form-layout">
        <div class="contact-panel">
            <div>
                <p class="eyebrow">Start a conversation</p>
                <h2>Let’s find the right solution together.</h2>
                <p>Send us a note and tell us a little about how we can help. We’ll get back to you as soon as we can.</p>
            </div>
            <a class="contact-link" href="mailto:<?= e($contactEmail) ?>">
                <?= e($contactEmail) ?>
            </a>
        </div>

        <div class="contact-form-wrap">
            <?php if ($messageSent): ?>
                <p class="form-alert form-alert-success" role="status">Thanks for reaching out. Your message has been sent.</p>
            <?php endif; ?>

            <?php if (isset($errors['_csrf'])): ?>
                <p class="form-alert form-alert-error" role="alert"><?= e($errors['_csrf']) ?></p>
            <?php endif; ?>
            <?php if (isset($errors['_form'])): ?>
                <p class="form-alert form-alert-error" role="alert"><?= e($errors['_form']) ?></p>
            <?php endif; ?>

            <form class="contact-form" method="post" action="contact.php">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

                <div class="form-field">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" autocomplete="name" required
                           value="<?= e($values['name']) ?>"
                           <?= isset($errors['name']) ? 'aria-invalid="true" aria-describedby="name-error"' : '' ?>>
                    <?php if (isset($errors['name'])): ?>
                        <p class="field-error" id="name-error"><?= e($errors['name']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           value="<?= e($values['email']) ?>"
                           <?= isset($errors['email']) ? 'aria-invalid="true" aria-describedby="email-error"' : '' ?>>
                    <?php if (isset($errors['email'])): ?>
                        <p class="field-error" id="email-error"><?= e($errors['email']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="phone">Phone <span class="field-optional">(optional)</span></label>
                    <input id="phone" name="phone" type="tel" autocomplete="tel"
                           value="<?= e($values['phone']) ?>"
                           <?= isset($errors['phone']) ? 'aria-invalid="true" aria-describedby="phone-error"' : '' ?>>
                    <?php if (isset($errors['phone'])): ?>
                        <p class="field-error" id="phone-error"><?= e($errors['phone']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="service">Service</label>
                    <select id="service" name="service" required
                            <?= isset($errors['service']) ? 'aria-invalid="true" aria-describedby="service-error"' : '' ?>>
                        <option value="">Choose a service</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= e($service['slug']) ?>"
                                <?= $values['service'] === $service['slug'] ? 'selected' : '' ?>>
                                <?= e($service['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['service'])): ?>
                        <p class="field-error" id="service-error"><?= e($errors['service']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="6" required
                              <?= isset($errors['message']) ? 'aria-invalid="true" aria-describedby="message-error"' : '' ?>><?= e($values['message']) ?></textarea>
                    <?php if (isset($errors['message'])): ?>
                        <p class="field-error" id="message-error"><?= e($errors['message']) ?></p>
                    <?php endif; ?>
                </div>

                <button class="button" type="submit">Send message</button>
            </form>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
