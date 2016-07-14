<?php
/* @var $panel bashkarev\swiftmailer\debug\MailPanel */
/* @var $searchModel bashkarev\swiftmailer\debug\Mail */
/* @var $dataProvider CArrayDataProvider */

?>
    <h1>Email messages</h1>
<?php
Yii::setPathOfAlias('{mailViewsPath}', __DIR__);
Yii::app()->controller->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => '{mailViewsPath}._item',
]); ?>