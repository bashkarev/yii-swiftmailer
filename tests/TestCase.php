<?php


abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Clean up after test.
     * By default the application created with [[mockApplication]] will be destroyed.
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->destroyApplication();
    }

    /**
     * Populates Yii::app() with a new application
     * The application will be destroyed on tearDown() automatically.
     * @param array $config The application configuration, if needed
     * @param string $appClass name of the application class to create
     */
    protected function mockApplication($config = [], $appClass = '\CWebApplication')
    {
        if (Yii::app() !== null) {
            $this->destroyApplication();
        }
        Yii::createApplication($appClass, \CMap::mergeArray([
            'id' => 'testapp',
            'basePath' => __DIR__,
        ], $config));
    }

    /**
     * Destroys application.
     */
    protected function destroyApplication()
    {
        \Yii::setApplication(null);
    }
}
