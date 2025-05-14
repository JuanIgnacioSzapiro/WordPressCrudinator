<?php
class Mailing
{
    private $email, $subject, $message, $headers;

    // Getters
    public function get_email()
    {
        return $this->email;
    }

    public function get_subject()
    {
        return $this->subject;
    }

    public function get_message()
    {
        return $this->message;
    }

    public function get_headers()
    {
        return $this->headers;
    }

    // Setters
    public function set_email($email)
    {
        $this->email = $email;
    }

    public function set_subject($subject)
    {
        $this->subject = $subject;
    }

    public function set_message($message)
    {
        $this->message = $message;
    }

    public function set_headers($headers)
    {
        $this->headers = $headers;
    }

    function __construct($email, $subject, $message, $headers = array('Content-Type: text/html; charset=UTF-8'))
    {
        $this->set_email($email);
        $this->set_subject($subject);
        $this->set_message($message);
        $this->set_headers($headers);
    }

    function configurar_smtp($phpmailer)
    {
        $phpmailer->isSMTP();

        // Configuración para MailHog (desarrollo)
        $phpmailer->Host = 'localhost';
        $phpmailer->Port = 1025;
        $phpmailer->SMTPAuth = false;

        // Configuración para producción (si existen globals)
        if (isset($GLOBALS['smtp_host'])) {
            $phpmailer->Host = $GLOBALS['smtp_host'];
            $phpmailer->Port = $GLOBALS['smtp_puerto'];
            $phpmailer->SMTPAuth = true;
            $phpmailer->Username = $GLOBALS['mail'];
            $phpmailer->Password = $GLOBALS['clave'];
            $phpmailer->SMTPSecure = $GLOBALS['smtp_secure'];
        }

        $phpmailer->SMTPDebug = 2;
    }

    function mandar_mail()
    {
        return wp_mail(
            $this->get_email(),
            $this->get_subject(),
            $this->get_message(),
            $this->get_headers()
        );
    }
}