<?php
/* @var $panel bashkarev\swiftmailer\MailPanel */
/* @var $mailCount integer */
if ($mailCount): ?>
    <div class="yii2-debug-toolbar-block">
        <a href="<?= $panel->getUrl() ?>">Mail <span class="label"><?= $mailCount ?></span></a>
    </div>
<?php endif ?>