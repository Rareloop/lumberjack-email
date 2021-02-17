<?php

namespace Rareloop\Lumberjack\Email;

use Rareloop\Lumberjack\Facades\Config;
use Timber\Timber;

class Email
{
    public function sendHTML($to, $subject, $body, $replyTo = false, $attachments = [])
    {
        if (empty($to)) {
            throw new \Exception('No to address set for email');
        }
        /**
         * Filter the mail content type.
         */
        $setEmailContentType = function () {
            return 'text/html';
        };

        add_filter('wp_mail_content_type', $setEmailContentType);

        $retVal = $this->send($to, $subject, $body, $replyTo, $attachments);

        // Reset content-type to avoid conflicts -- https://core.trac.wordpress.org/ticket/23578
        remove_filter('wp_mail_content_type', $setEmailContentType);

        return $retVal;
    }

    public function sendHTMLFromTemplate($to, $subject, $template, $data = [], $replyTo = false, $attachments = [])
    {
        $body = Timber::compile($template, $data);
        return $this->sendHTML($to, $subject, $body, $replyTo, $attachments);
    }

    public function sendPlain($to, $subject, $body, $replyTo = false, $attachments = [])
    {
        return $this->send($to, $subject, $body, $replyTo, $attachments);
    }

    private function send($to, $subject, $body, $replyTo = false, $attachments = [])
    {
        $headers = [];

        if ($replyTo) {
            $headers[] = 'Reply-To: <'.$replyTo.'>';
        }

        $fromName = Config::get('email.from.name');
        $fromEmail = Config::get('email.from.email');

        if ($fromName && $fromEmail) {
            $headers[] = 'From: '.$fromName.' <'.$fromEmail.'>';
        }

        // If we're not in production then we should add a prefix to the subject line with the env
        if (Config::get('app.environment') !== 'production') {
            $subject = '['.strtoupper(Config::get('app.environment')).'] '.$subject;
        }

        return wp_mail($to, $subject, $body, $headers, $attachments);
    }
}
