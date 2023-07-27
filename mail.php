<?php
//require 'vendor/autoload.php'; // If you're using Composer (recommended)
// Comment out the above line if not using Composer
require("sendgrid-php/sendgrid-php.php");
// If not using Composer, uncomment the above line and
// download sendgrid-php.zip from the latest release here,
// replacing <PATH TO> with the path to the sendgrid-php.php file,
// which is included in the download:
// https://github.com/sendgrid/sendgrid-php/releases

$email_to = "jalorer@gmail.com";
$email_subject = "Contacto SiseVende.cl";

if(!isset($_POST['name']) ||
    !isset($_POST['rut']) ||
    !isset($_POST['email']) ||
    !isset($_POST['rubro']) ||
    !isset($_POST['web']) ||
    !isset($_POST['message'])) {
  die('Lo sentimos pero parece haber un problema con los datos enviados.');
}

$name = $_POST['name'];
$rut = $_POST['rut'];
$email_from = $_POST['email'];
$rubro = $_POST['rubro'];
$message = $_POST['message'];
$web = $_POST['web'];
$error_message = "";

$string_exp = "/^[A-Za-z .'-]+$/";
 
if(!preg_match($string_exp,$name)) {
  $error_message .= 'El formato del nombre proporcionado no es válido.<br>';
}
$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
if(!preg_match($email_exp,$email_from)) {
  $error_message .= 'La dirección de correo proporcionada no es válida.<br>';
}
if(!preg_match('/^[0-9]+$/', $rut)) {
  $error_message .= 'El rut proporcionado no es válido.<br>';
}
if(strlen($error_message) > 0) {
  die($error_message);
}

function clean_string($string) {
  $bad = array("content-type","bcc:","to:","cc:","href");
  return str_replace($bad,"",$string);
}

$email_message = "Nombre: ".clean_string($name)."<br>";
$email_message .= "Email: ".clean_string($email_from)."<br>";
$email_message .= "Rut: ".clean_string($rut)."<br>";
$email_message .= "Rubro: ".clean_string($rubro)."<br>";
$email_message .= "Mensaje: ".clean_string($message)."<br>";
$email_message .= "Sitio web: ".clean_string($web)."<br>";
$email_message .= "Mensaje enviado desde el formulario de contacto de SiseVende.cl";

$email = new \SendGrid\Mail\Mail(); 
$email->setFrom($email_from, $name);
$email->setSubject($email_subject);
$email->addTo($email_to, "Contacto SiseVende");
$email->addContent("text/html", $email_message);

$sendgrid = new \SendGrid('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');
try {
  $response = $sendgrid->send($email);
  print '¡Gracias! Nos pondremos en contacto contigo a la brevedad.';
} catch (Exception $e) {
  print 'Lo sentimos, ha ocurrido un error: '. $e->getMessage();
}