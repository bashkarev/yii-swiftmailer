SwiftMailer Extension for Yii1, imported yii2-swiftmailer
===============================

This extension provides a [SwiftMailer](http://swiftmailer.org/) mail solution for [Yii framework 1](http://www.yiiframework.com).


Installation
------------

Either run

```
composer require bashkarev/yii-swiftmailer
```

or add

```json
"bashkarev/yii-swiftmailer": "~1.0.0"
```

to the require section of your composer.json.


Configuration
-------------

Mail component configuration depends on the extension you have chosen.
In general your application configuration should look like:

```php
return [
    //....
    'components' => [
        'mailer' => [
            'class' => 'bashkarev\swiftmailer\swift\Mailer',
        ],
    ],
];
```
example SMTP:
```php
return [
    //....
    'components' => [
        'mailer' => [
            'class' => 'bashkarev\swiftmailer\swift\Mailer',
            //'viewPath' => 'application.mail' //default path to views
            'transport' => [
                'host' => 'smtp.example.ru.',
                'username' => 'username',
                'password' => 'password',
                'port' => '465',
                'encryption' => 'ssl',
            ],
            'messageConfig' => [
                'from' => ['example@example.ru' => 'Example Name']
            ]
        ],
    ],
];

```

Debug panel
-----
usage panel [zhuravljov/yii2-debug](https://github.com/zhuravljov/yii2-debug)
```php
return [
    //....
    'components' => [
        'debug' => [
            'class' => 'application.vendor.zhuravljov.yii2-debug.Yii2Debug',
            'enabled' => YII_DEBUG,
            'panels' =>[
                'mail' => [
                    'class' => 'bashkarev\swiftmailer\debug\MailPanel'
                ]
            ],
        ],
    ],
];

```


Basic usage
-----------

Once the 'mailer' component is configured, you can use the following code to send an email message:

```php
Yii::app()->mailer->compose()
    ->setFrom('from@domain.com')
    ->setTo('to@domain.com')
    ->setSubject('Message subject')
    ->setTextBody('Plain text content')
    ->setHtmlBody('<b>HTML content</b>')
    ->send();
```
You may also send several messages at once:

```php
$messages = [];
foreach ($users as $user) {
    $messages[] = Yii::app()->mailer->compose()
        // ...
        ->setTo($user->email);
}
Yii::$app->mailer->sendMultiple($messages);
```


Composing mail content
----------------------

Yii allows composition of the actual mail messages content via special view files.
By default these files should be located at 'application.mail' path.

Example mail view file layout:
/protected/mail/layouts/html.php
```php
<?php
/* @var $this \bashkarev\swiftmailer\View */
/* @var $message \bashkarev\swiftmailer\MessageInterface */
/* @var $content string main view render result */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::app()->charset ?>" />
    <style type="text/css">
        .heading {...}
        .list {...}
        .footer {...}
    </style>
</head>
<body>
    <?= $content ?>
    <div class="footer">With kind regards, <?= Yii::app()->name ?> team</div>
</body>
</html>
```

Example mail view file content:

/protected/mail/test.php
```php
<?php
/* @var $this \bashkarev\swiftmailer\View */
/* @var $message \bashkarev\swiftmailer\MessageInterface */
?>

<div class="mail-test">
    User id:<?= $model->id ?>
</div>
```

```php
Yii::app()->mailer->compose('test', ['model'=>User::model()->findByPk(1)])
    ->setFrom('from@domain.com')
    ->setTo('to@domain.com')
    ->setSubject('Message subject')
    ->send();
```


File attachment
---------------

You can add attachments to message using methods `attach()` and `attachContent()`:

```php
$message = Yii::app()->mailer->compose();

// Attach file from local file system:
$message->attach('/path/to/source/file.pdf');

// Create attachment on-the-fly
$message->attachContent('Attachment content', ['fileName' => 'attach.txt', 'contentType' => 'text/plain']);
```


Embedding images
----------------

You can embed images into the message content using `embed()` method. This method returns the attachment id,
which should be then used at 'img' tag.
This method is easy to use when composing message content via view file:

```php
Yii::app()->mailer->compose('embed-email', ['imageFileName' => '/path/to/image.jpg'])
    // ...
    ->send();
```

Then inside the view file you can use the following code:

```php
<img src="<?= $message->embed($imageFileName); ?>">
```