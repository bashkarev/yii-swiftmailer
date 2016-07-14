<?php

namespace bashkarev\swiftmailer\debug;


class Mail extends \CFormModel
{

    /**
     * @var string from attribute input search value
     */
    public $from;
    /**
     * @var string to attribute input search value
     */
    public $to;
    /**
     * @var string reply attribute input search value
     */
    public $reply;
    /**
     * @var string cc attribute input search value
     */
    public $cc;
    /**
     * @var string bcc attribute input search value
     */
    public $bcc;
    /**
     * @var string subject attribute input search value
     */
    public $subject;
    /**
     * @var string body attribute input search value
     */
    public $body;
    /**
     * @var string charset attribute input search value
     */
    public $charset;
    /**
     * @var string headers attribute input search value
     */
    public $headers;
    /**
     * @var string file attribute input search value
     */
    public $file;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from', 'to', 'reply', 'cc', 'bcc', 'subject', 'body', 'charset'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'from' => 'From',
            'to' => 'To',
            'reply' => 'Reply',
            'cc' => 'Copy receiver',
            'bcc' => 'Hidden copy receiver',
            'subject' => 'Subject',
            'charset' => 'Charset'
        ];
    }

    /**
     * Returns data provider with filled models. Filter applied if needed.
     * @param array $params
     * @param array $models
     * @return \CArrayDataProvider
     */
    public function search($params, $models)
    {
        $dataProvider = new \CArrayDataProvider($models, [
            'id'=>'mail',
            'keyField'=>'to',
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => ['from', 'to', 'reply', 'cc', 'bcc', 'subject', 'body', 'charset'],
            ],
        ]);
        $this->setAttributes($params);
        return $dataProvider;
    }

}