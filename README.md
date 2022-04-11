# Symfony Mailer library

This Symfony library intends to offer an abstraction between code and the actual mailer service. This allows us to switch between different strategy just by changing configuration.

Mails are defined by extending the `MailTemplate` class, and sent by calling `MailService->sendMail(template)` method.

## Requirements

* `PHP >= 7.1`
* `symfony >= 4.0`


## Installation

```
composer require beapp/mailer-core
```

Then choose one of the following transport layer :

**Direct calls :**
```
composer require beapp/mailer-transport-mailgun
composer require beapp/mailer-transport-mandrill
```

**Send command to worker :**
```
composer require beapp/mailer-transport-beanstalk
composer require beapp/mailer-transport-rabbitmq
```


## Getting started !

In this sample, we'll use the Mailgun transport.

Declare your transport and `MailerService` as Symfony's service.

```
  app.mailer:
    class: Beapp\Email\Core\MailerService
    arguments:
      - '@app.mailer.transport.mailgun'
      - '@router'
      - '@translator'
      - '@templating'
      - '%mailgun_sender_email%'
      - '%mailgun_sender_name%'
      
  app.mailer.transport.mailgun:
    class: Beapp\Email\Transport\MailgunMailerTransport
    arguments:
      - '%env(MAILGUN_API_KEY)%'
      - '%env(MAILGUN_DOMAIN)%'
```

Implement a template for your specific mail to send.

```
class AccountValidationMailTemplate implements MailTemplate
{
    /** @var string */
    private $email;
    /** @var string */
    private $fullName;
    /** @var string */
    private $token;

    public function __construct(string $email, string $fullName, string $token)
    {
        $this->email = $email;
        $this->fullName = $fullName;
        $this->token = $token;
    }

    public function build(RouterInterface $router, TranslatorInterface $translator, Environment $templating): Mail
    {
        $url = $router->generate('account_validate', ['token' => $this->token], UrlGeneratorInterface::ABSOLUTE_URL);

        return new Mail(
            $this->email,
            $this->fullName,
            $translator->trans('mail.validation.subject'),
            $translator->trans('mail.validation.text_message'),
            $templating->render('mail/account_validation.html.twig', ['data' => [
                '%name%' => $this->fullName,
                '%url%' => $url,
            ]])
        );
    }
}
```

Send the email from your code.

```
$mailerService->sendMail(new AccountValidationMailTemplate('client@myapp.com', 'Client', 'activation-token'));
```

### Add an attachment to your mail 

 To send files with your mail, just set an array of `Symfony\Component\HttpFoundation\File\File` like this : 
 
```
$mail = new Mail();

$attachment = [new File('somefilepath')];

$mail->setAttachments($attachment);
```
