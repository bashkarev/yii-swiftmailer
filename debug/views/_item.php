<?php
/* @var $data array */
?>
<div style="margin-bottom: 10px">

    <?php
    $this->widget('zii.widgets.CDetailView', [
        'data' => $data,
        'attributes' => [
            'headers',
            'from',
            'to',
            'charset',
            [
                'attribute' => 'time',
                'format' => 'datetime',
            ],
            'subject',
            [
                'attribute' => 'body',
                'label' => 'Text body',
            ],
            [
                'attribute' => 'isSuccessful',
                'label' => 'Successfully sent',
                'value' => $data['isSuccessful'] ? 'Yes' : 'No'
            ],
            'reply',
            'bcc',
            'cc',
            [
                'label' => 'file',
                'type' => 'raw',
                'value' => CHtml::link('Download eml', ['download-mail', 'file' => $data['file']]),
            ],
        ],
    ]);
    ?>
</div>
