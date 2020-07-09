<?php  defined('BARRIO') or die('Sin accesso a este script.');


/**
 * ====================================================
 * {Contact} // usa el del config.php
 * {Contact mail='nakome@demo.com'}
 * ====================================================
 */
Barrio::addShortcode('Contact', function ($atributos) {
    extract($atributos);
    
    // atributos
    $mail = (isset($mail)) ? $mail : Barrio::$config['email'];
    
    $arrLang = array(
        'email'     => 'Correo Electrónico',
        'subject'   => 'Asusnto',
        'message'   => 'Mensaje',
        'send'      => 'Enviar Correo',
        'error'     => 'Lo siento hubo un error al enviar el mensaje pruebe otra vez.',
        'success'   => 'Gracias el mensaje ha sido enviado.',
        'info'      => 'Por favor confirma que no eres un robot'
    );

    $language = $arrLang;

    $error = '';
    if (isset($_POST['Submit'])) {
        // vars
        $recepient = $mail;
        $sitename = Barrio::urlBase();
        $service = trim($_POST["subject"]);
        $email = trim($_POST["email"]);
        $text = trim($_POST["message"]);

        $message = "Asunto: $service \n\mensaje: $text";
        $pagetitle = "Nuevo mensaje desde \"$sitename\"";

        $headers = "From: {$email}\r\n";
        $headers .= "Reply-To: {$mail}\r\n";
        $headers .= "Return-Path: {$email}\r\n";
        $headers .= "Content-type: text/plain; charset=\"utf-8\"";

        if (isset($_POST['robot'])) {
            // send mail
            if (mail($recepient, $pagetitle, $message, $headers)) {
                // success
                $error = '<p><strong style="color:blue"> 😀 '.$language['success'].' ....</strong></p>';
            } else {
                // error
                $error = '<p><strong style="color:red"> 😢'.$language['error'].'..</strong></p>';
            };
        } else {
            // error
            $error = '<p><strong style="color:red"> 😢 '.$language['info'].'..</strong></p>';
        };
    }

    // show error
    $html  = $error;
    $html .= '  <form method="post"  name="form1">';
    $html .= '      <p>';
    $html .= '        <label>'.$language['email'].'</label><br/>';
    $html .= '        <input type="email" name="email" required>';
    $html .= '      </p>';
    $html .= '      <p>';
    $html .= '        <label>'.$language['subject'].'</label><br/>';
    $html .= '        <input type="text" name="subject" required>';
    $html .= '      </p>';
    $html .= '      <p>';
    $html .= '        <label>'.$language['message'].'</label><br/>';
    $html .= '        <textarea  name="message" cols="35" rows="8" required></textarea>';
    $html .= '      </p>';
    $html .= '      <p>';
    $html .= '        <label><input type="checkbox" name="robot"/> No soy un robot.</label>';
    $html .= '      </p>';
    $html .= '      <p>';
    $html .= '          <input type="submit" name="Submit" value="'.$language['send'].'">';
    $html .= '      </p>';
    $html .= '  </form>';
    return $html;
});
