<?php

namespace bashkarev\swiftmailer;

use Yii;

/**
 * BaseMessage serves as a base class that implements the [[send()]] method required by [[MessageInterface]].
 * @author Dmitriy Bashkarev <dmitriy@bashkarev.com>
 * @author Paul Klimov <klimov.paul@gmail.com>
 */
abstract class BaseMessage extends \CComponent implements MessageInterface
{
    /**
     * @var MailerInterface the mailer instance that created this message.
     * For independently created messages this is `null`.
     */
    public $mailer;


    /**
     * Sends this email message.
     * @param MailerInterface $mailer the mailer that should be used to send this message.
     * If no mailer is given it will first check if [[mailer]] is set and if not,
     * the "mail" application component will be used instead.
     * @return boolean whether this message is sent successfully.
     */
    public function send(MailerInterface $mailer = null)
    {
        if ($mailer === null && $this->mailer === null) {
            $mailer = Yii::app()->getComponent('mailer');
        } elseif ($mailer === null) {
            $mailer = $this->mailer;
        }
        return $mailer->send($this);
    }

    /**
     * PHP magic method that returns the string representation of this object.
     * @return string the string representation of this object.
     */
    public function __toString()
    {
        // __toString cannot throw exception
        // use trigger_error to bypass this limitation
        try {
            return $this->toString();
        } catch (\Exception $e) {
            return '';
        }
    }
}