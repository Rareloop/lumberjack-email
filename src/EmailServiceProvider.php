<?php

namespace Rareloop\Lumberjack\Email;

use Rareloop\Lumberjack\Config;
use Rareloop\Lumberjack\Email\Email;
use Rareloop\Lumberjack\Providers\ServiceProvider;

class EmailServiceProvider extends ServiceProvider
{
    public function boot(Config $config)
    {
        if ($this->useSMTP($config)) {
            $this->setupSMTP($config);
        }
    }

    private function useSMTP(Config $config)
    {
        $host = $config->get('email.smtp.hostname');
        $username = $config->get('email.smtp.username');
        $password = $config->get('email.smtp.password');

        return (!empty($host) && !empty($username) && !empty($password));
    }

    private function setupSMTP(Config $config)
    {
        add_action('phpmailer_init', function ($phpmailer) use ($config) {
            $phpmailer->isSMTP();
            $phpmailer->Host = $config->get('email.smtp.hostname');
            $phpmailer->Username = $config->get('email.smtp.username');
            $phpmailer->Password = $config->get('email.smtp.password');

            $auth = $config->get('email.smtp.auth');
            $port = $config->get('email.smtp.port');

            if (!empty($auth)) {
                $phpmailer->SMTPAuth = $auth;
            }

            if (!empty($port)) {
                $phpmailer->Port = $port;
            }
        });
    }

    public function register()
    {
        $this->app->bind('email', new Email);
    }
}
