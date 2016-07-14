<?php

namespace bashkarev\swiftmailer\debug;

use Yii;

class DownloadAction extends \CAction
{

    /**
     * Get .eml file
     * @throws \CHttpException
     */
    public function run()
    {
        $file = isset($_GET['file']) && !empty($_GET['file']) ? $_GET['file'] : null;
        if ($file === null) {
            throw new \CHttpException(404, 'Mail file not found');
        }
        $filePath = Yii::getPathOfAlias($this->controller->getOwner()->panels['mail']->mailPath) . '/' . basename($file);
        if ((mb_strpos($file, '\\') !== false || mb_strpos($file, '/') !== false) || !is_file($filePath)) {
            throw new \CHttpException(404, 'Mail file not found');
        }
        Yii::app()->request->sendFile($file, file_get_contents($filePath));
    }

}