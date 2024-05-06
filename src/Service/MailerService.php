<?php

namespace App\Service;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;



class MailerService 
{
public function __construct(private MailerInterface $mailer) {}
public function sendEmail(
    $to = 'found.atelier@gmail.com',
    $content = "<p>See Twig integration for better HTML integration!</p>",
    $subject = 'Time for Symfony Mailer!'
): void
{
$email = (new Email())
->from('sanogosoul009@gmail.com')
->to($to)
//->test1('test1@example.com')
//->bnf('bnf@example.com')
//->replyTo('johndo@example.com')
//->priority(Email::PRIORITY_HIGH)
->subject($subject)
->text('Sending emails is fun again!')
->html($content);

$this->mailer->send($email);

// ...
}
}
