<?php

namespace Rareloop\Lumberjack\Email;

use Rareloop\Lumberjack\Config;
use Rareloop\Lumberjack\Email\Email;
use Rareloop\Lumberjack\Facades\Log;
use Rareloop\Lumberjack\Providers\ServiceProvider;

class EmailServiceProvider extends ServiceProvider
{
    public function boot(Config $config)
    {
        if ($this->useSMTP($config)) {
            $this->setupSMTP($config);
        }

        $this->addWpMailErrorHandler();
    }

    private function useSMTP(Config $config)
    {
        $host = $config->get('email.smtp.hostname');
        $authenticate = $config->get('email.smtp.auth', true) === true;

        $username = $config->get('email.smtp.username');
        $password = $config->get('email.smtp.password');

        if (!empty($host) && !$authenticate) {
            return true;
        }

        return (!empty($host) && !empty($username) && !empty($password));
    }

    private function setupSMTP(Config $config)
    {
        add_action('phpmailer_init', function ($phpmailer) use ($config) {
            $phpmailer->isSMTP();
            $phpmailer->Host = $config->get('email.smtp.hostname');

            $auth = $config->get('email.smtp.auth', true);
            $port = $config->get('email.smtp.port');

            $phpmailer->SMTPAuth = $auth === true;

            if ($phpmailer->SMTPAuth) {
                $phpmailer->Username = $config->get('email.smtp.username');
                $phpmailer->Password = $config->get('email.smtp.password');
            }

            if (!empty($port)) {
                $phpmailer->Port = $port;
            }
        });
    }

    private function addWpMailErrorHandler()
    {
        // add the action 
        add_action('wp_mail_failed', function ($error) {
            Log::error('Error sending email via `wp_mail()`');
            Log::error(print_r($error, true));
        });
    }

    public function register()
    {
        $this->app->bind('email', new Email);
    }
}
