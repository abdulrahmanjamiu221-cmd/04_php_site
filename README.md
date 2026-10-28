# King Manu Tech Hub

King Manu Tech Hub is a small PHP website for a technology services business. It presents the team and services, provides service detail pages, and accepts contact requests that are saved locally as JSON Lines.

## Features

- Home, about, services, service detail, and contact pages
- Data-driven service cards and team profiles
- Contact form with server-side validation, CSRF protection, and sticky values after validation errors
- Contact submissions saved to `storage/messages.jsonl`
- Password-protected messages page with newest-first sorting and escaped output
- Responsive styling for desktop and mobile

## Tech stack

- PHP 8+
- HTML5
- CSS3
- JSON Lines for local contact message storage
- XAMPP / Apache for local development

## Run with XAMPP

1. Install and start **Apache** from the XAMPP Control Panel.
2. Make sure the project folder is located at:

   `C:\xampp\htdocs\swdprojects\04_php_site`

3. Open the site at:

   `http://localhost/swdprojects/04_php_site/`

4. Open the local messages inbox at:

   `http://localhost/swdprojects/04_php_site/messages.php`

5. The learning password is set in `includes/config.php` as `$messagesPassword`. Change it before using the page, even locally.

The `storage` directory is created automatically when the first contact form submission is saved. Apache access to the directory is denied by `storage/.htaccess`.

## Project structure

```text
04_php_site/
├── about.php
├── contact.php
├── index.php
├── messages.php
├── service.php
├── services.php
├── assets/
│   └── css/
│       └── style.css
├── includes/
│   ├── config.php
│   ├── data.php
│   ├── footer.php
│   ├── functions.php
│   └── header.php
└── storage/
    ├── .htaccess
    └── messages.jsonl  (created when a message is submitted)
```

## What I learned

- **PHP and server-side form handling:** _[Add what you learned here.]_
- **Validation and escaping user input:** _[Add what you learned here.]_
- **Sessions and CSRF tokens:** _[Add what you learned here.]_
- **Saving and reading JSON Lines data:** _[Add what you learned here.]_
- **One thing I would improve next:** _[Add your idea here.]_

## Learning-only password note

The messages page uses a hard-coded password in `includes/config.php` because this project is intended for local learning. This is **not production-grade authentication**: the password is stored in plain text in the source code, and this basic gate does not include features such as secure password hashing and account management, rate limiting, or hardened session and deployment controls. Do not deploy the page or use this approach to protect real personal data. A production system should use a proper authentication mechanism and secure configuration outside the web root.
