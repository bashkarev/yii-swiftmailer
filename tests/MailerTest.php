<?php


use bashkarev\swiftmailer\swift\Mailer;

/**
 * @group vendor
 * @group mail
 * @group swiftmailer
 */
class MailerTest extends TestCase
{
    public function setUp()
    {
        $this->mockApplication([
            'components' => [
                'email' => [
                    'class' => 'bashkarev\swiftmailer\swift\Mailer'
                ]
            ]
        ]);
    }

    // Tests :

    public function testSetupTransport()
    {
        $mailer = new Mailer();

        $transport = \Swift_MailTransport::newInstance();
        $mailer->setTransport($transport);
        $this->assertEquals($transport, $mailer->getTransport(), 'Unable to setup transport!');
    }

    /**
     * @depends testSetupTransport
     */
    public function testConfigureTransport()
    {
        $mailer = new Mailer();

        $transportConfig = [
            'class' => 'Swift_SmtpTransport',
            'host' => 'localhost',
            'username' => 'username',
            'password' => 'password',
        ];
        $mailer->setTransport($transportConfig);
        $transport = $mailer->getTransport();
        $this->assertTrue(is_object($transport), 'Unable to setup transport via config!');
        $this->assertEquals($transportConfig['class'], get_class($transport), 'Invalid transport class!');
        $this->assertEquals($transportConfig['host'], $transport->getHost(), 'Invalid transport host!');
    }

    /**
     * @depends testConfigureTransport
     */
    public function testConfigureTransportConstruct()
    {
        $mailer = new Mailer();

        $class = 'Swift_SmtpTransport';
        $host = 'some.test.host';
        $port = 999;
        $transportConfig = [
            'class' => $class,
            'host' => $host,
            'port' => $port
        ];
        $mailer->setTransport($transportConfig);
        $transport = $mailer->getTransport();
        $this->assertTrue(is_object($transport), 'Unable to setup transport via config!');
        $this->assertEquals($class, get_class($transport), 'Invalid transport class!');
        $this->assertEquals($host, $transport->getHost(), 'Invalid transport host!');
        $this->assertEquals($port, $transport->getPort(), 'Invalid transport host!');
    }

    /**
     * @depends testConfigureTransportConstruct
     */
    public function testConfigureTransportWithPlugins()
    {
        $mailer = new Mailer();

        $pluginClass = 'Swift_Plugins_ThrottlerPlugin';
        $rate = 10;

        $transportConfig = [
            'class' => 'Swift_SmtpTransport',
            'plugins' => [
                [
                    'class' => $pluginClass,
                    'constructArgs' => [
                        $rate,
                    ],
                ],
            ],
        ];
        $mailer->setTransport($transportConfig);
        $transport = $mailer->getTransport();
        $this->assertTrue(is_object($transport), 'Unable to setup transport via config!');
        $this->assertContains(':' . $pluginClass . ':', print_r($transport, true), 'Plugin not added');
    }

    public function testGetSwiftMailer()
    {
        $mailer = new Mailer();
        $this->assertTrue(is_object($mailer->getSwiftMailer()), 'Unable to get Swift mailer instance!');
    }
}
