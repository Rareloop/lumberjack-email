<?php

namespace Rareloop\Lumberjack\Email\Test;

use Blast\Facades\FacadeFactory;
use Brain\Monkey;
use Brain\Monkey\Functions;
use Mockery;
use PHPUnit\Framework\TestCase;
use Rareloop\Lumberjack\Application;
use Rareloop\Lumberjack\Config;
use Rareloop\Lumberjack\Email\Email;
use Rareloop\Lumberjack\Email\EmailServiceProvider;
use Rareloop\Lumberjack\Email\Facades\Email as EmailFacade;
use Rareloop\Lumberjack\Http\Lumberjack;

class EmailServiceProviderTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function setUp()
    {
        parent::setUp();
        Monkey\setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
        Monkey\tearDown();
    }

    /** @test */
    public function test_facade()
    {
        $app = new Application();
        FacadeFactory::setContainer($app);

        $provider = new EmailServiceProvider($app);
        $provider->register();

        $this->assertTrue($app->has('email'));
        $this->assertInstanceOf(Email::class, $app->get('email'));
    }

    /** @test */
    public function can_define_smtp_settings_in_config()
    {
        $app = new Application();

        $config = new Config;

        $config->set('email.smtp', [
            'hostname' => 'the-hostname',
            'username' => 'the-username',
            'password' => 'the-password',
            'port' => 'the-port',
            'auth' => 'the-Auth',
        ]);

        Functions\expect('add_action')
            ->once()
            ->with('phpmailer_init', Mockery::type('callable'));

        $provider = new EmailServiceProvider($app);
        $provider->boot($config);
    }
}
