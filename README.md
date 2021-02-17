# Lumberjack Email

This package provides a simple way to send rich emails using Twig templates. A small wrapper around `wp_mail()`;

Once installed, register the Service Provider in `config/app.php`:

```php
'providers' => [
    ...

    Rareloop\Lumberjack\Email\EmailServiceProvider::class,

    ...
],
```

## Sending Emails

You then have access to the `Rareloop\Lumberjack\Email\Facades\Email` facade and can create emails:

### Send an HTML email

```php
use Rareloop\Lumberjack\Email\Facades\Email;

Email::sendHTML(
    'recipient@mail.com',
    'Email Subject line',
    '<p>Email body goes here</p>'
);

// Or with a reply-to email
Email::sendHTML(
    'recipient@mail.com',
    'Email Subject line',
    '<p>Email body goes here</p>',
    'reply-to@mail.com'
);
```

### Send an HTML email using a Twig template
The twig file will be compiled using Timber before sending.

```php
use Rareloop\Lumberjack\Email\Facades\Email;

Email::sendHTMLFromTemplate(
    'recipient@mail.com',
    'Email Subject line',
    'email.twig',
    [ 'name' => 'Jane Doe' ] // Twig context
);
```

### Send a plain text email

```php
use Rareloop\Lumberjack\Email\Facades\Email;

Email::sendPlain(
    'recipient@mail.com',
    'Email Subject line',
    'Plain text body',
);
```

### Send an email with attachments
Each send method accepts an optional `attachments` parameter which will be passed on to `wp_mail` as it is received.

```php
use Rareloop\Lumberjack\Email\Facades\Email;

Email::sendPlain(
    'recipient@mail.com',
    'Email Subject line',
    'Plain text body',
    false,  // Set reply-to to false if you don't need it, necessary because attachments is the final argument
    ['/path/to/file.pdf']
);
```

## Config
You can also specify an SMTP server to use for emails by creating a `config/email.php` file:

```php
return [
    'smtp' => [
        'hostname' => '',
        'username' => '',
        'password' => '',
        'auth' => true|false,
        'port' => '',
    ],
];
```