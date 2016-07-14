<?php

namespace bashkarev\swiftmailer\debug;


use bashkarev\swiftmailer\BaseMailer;
use Yii;

class MailPanel extends \Yii2DebugPanel
{

    /**
     * @var string path where all emails will be saved. should be an alias.
     */
    public $mailPath = 'application.runtime/debug/mail';

    /**
     * @var array current request sent messages
     */
    private $_messages = [];

    /**
     * @inheritdoc
     */
    public $actions = [
        'download-mail' => 'bashkarev\swiftmailer\debug\DownloadAction'
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Yii::app()->mailer->attachEventHandler(BaseMailer::EVENT_AFTER_SEND, function ($event) {
            /* @var $message MessageInterface */
            $message = $event->params['message'];
            $messageData = [
                'isSuccessful' => $event->params['isSuccessful'],
                'from' => $this->convertParams($message->getFrom()),
                'to' => $this->convertParams($message->getTo()),
                'reply' => $this->convertParams($message->getReplyTo()),
                'cc' => $this->convertParams($message->getCc()),
                'bcc' => $this->convertParams($message->getBcc()),
                'subject' => $message->getSubject(),
                'charset' => $message->getCharset(),
            ];

            // add more information when message is a SwiftMailer message
            if ($message instanceof \bashkarev\swiftmailer\swift\Message) {
                /* @var $swiftMessage \Swift_Message */
                $swiftMessage = $message->getSwiftMessage();

                $body = $swiftMessage->getBody();
                if (empty($body)) {
                    $parts = $swiftMessage->getChildren();
                    foreach ($parts as $part) {
                        if (!($part instanceof \Swift_Mime_Attachment)) {
                            /* @var $part \Swift_Mime_MimePart */
                            if ($part->getContentType() == 'text/plain') {
                                $messageData['charset'] = $part->getCharset();
                                $body = $part->getBody();
                                break;
                            }
                        }
                    }
                }

                $messageData['body'] = $body;
                $messageData['time'] = $swiftMessage->getDate();
                $messageData['headers'] = $swiftMessage->getHeaders();

            }

            // store message as file
            $fileName = $event->sender->generateMessageFileName();
            $path = Yii::getPathOfAlias($this->mailPath);
            if (!is_dir($path)) {
                \CFileHelper::createDirectory($path);
            }
            file_put_contents($path . '/' . $fileName, $message->toString());
            $messageData['file'] = $fileName;

            $this->_messages[] = $messageData;
        });

    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Mail';
    }

    /**
     * @inheritdoc
     */
    public function getSummary()
    {
        return $this->render(__DIR__ . '/views/summary.php', ['panel' => $this, 'mailCount' => count($this->data)]);
    }

    /**
     * @inheritdoc
     */
    public function getDetail()
    {
        $searchModel = new Mail();
        $dataProvider = $searchModel->search($_GET, $this->data);
        return $this->render(__DIR__ . '/views/detail.php', [
            'panel' => $this,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        return $this->getMessages();
    }

    /**
     * Returns info about messages of current request. Each element is array holding
     * message info, such as: time, reply, bc, cc, from, to and other.
     * @return array messages
     */
    public function getMessages()
    {
        return $this->_messages;
    }

    private function convertParams($attr)
    {
        if (is_array($attr)) {
            $attr = implode(', ', array_keys($attr));
        }

        return $attr;
    }


}