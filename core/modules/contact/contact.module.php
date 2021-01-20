<?php
/*
 * Declara al principio del archivo, las llamadas a las funciones respetarán
 * estrictamente los indicios de tipo (no se lanzarán a otro tipo).
 */
declare (strict_types = 1);

/**
 * Acceso restringido
 */
defined("ACCESS") or die("No tiene acceso a este archivo");

use Action\Action as Action;
use Barrio\Barrio as Barrio;
use Shortcode\Shortcode as Shortcode;

/**
 * ====================================================
 * Guía rápida sobre cómo agregar a su sitio web:
 *  1. Abra el archivo: fcf-assets / fcf.config.php
 *  2. Agregue su dirección de correo electrónico a la parte resaltada a continuación:
 *   define ('EMAIL_TO', 'su dirección de correo electrónico aquí');
 *  3. Agregue su dirección de correo electrónico (esta dirección de correo electrónico debe ser conocida por su entorno
 *   de alojamiento) a la parte resaltada a continuación:
 *    define ('EMAIL_FROM', 'su dirección de correo electrónico aquí');
 *
 * [Contact] // usa el del config.php
 * ====================================================
 */
Shortcode::add('Contact', function () {

    $arrLang = array(
        'email' => 'Correo electronico',
        'name' => 'Nombre completo',
        'phone' => 'Telefono de contacto',
        'message' => 'Mensaje',
        'send' => 'Enviar Correo',
        'error' => 'Lo siento hubo un problema al enviarlo por favor intentelo otra vez',
        'success' => 'Gracias tu mensaje ha sido enviado',
    );
    // array lenguaje
    $lang = $arrLang;
    // url del modulo
    $urlAction = Barrio::urlBase() . '/core/modules/contact';
    $html = '<div class="fcf-form-wrap">
        <div id="fcf-form">
            <form class="fcf-form-class" method="post" action="' . $urlAction . '/fcf-assets/fcf.process.php">
                <div class="field">
                    <label for="Name" class="label has-text-weight-normal">' . $lang['name'] . '</label>
                    <div class="control">
                        <input type="text" name="Name" id="Name" class="input is-full-width" maxlength="100"
                            data-validate-field="Name">
                    </div>
                </div>
                <div class="field">
                    <label for="Email" class="label has-text-weight-normal">' . $lang['email'] . '</label>
                    <div class="control">
                        <input type="email" name="Email" id="Email" class="input is-full-width" maxlength="100"
                            data-validate-field="Email">
                    </div>
                </div>
                <div class="field">
                    <label for="Phone" class="label has-text-weight-normal">' . $lang['phone'] . '</label>
                    <div class="control">
                        <input type="tel" name="Phone" id="Phone" class="input is-full-width" maxlength="30"
                            data-validate-field="Phone">
                    </div>
                </div>
                <div class="field">
                    <label for="Message" class="label has-text-weight-normal">' . $lang['message'] . '</label>
                    <div class="control">
                        <textarea name="Message" id="Message" class="textarea" maxlength="3000" rows="5"
                            data-validate-field="Message"></textarea>
                    </div>
                </div>
                <div id="fcf-status" class="fcf-status"></div>
                <div class="field">
                    <div class="buttons">
                        <button id="fcf-button" type="submit" class="button is-link is-medium is-fullwidth">' . $lang['send'] . '</button>
                    </div>
                </div>
                <div class="fcf-attribution">Contact Form by <a href="https://www.freecontactform.com/" class="fcf-attribution-link">FreeContactForm</a></div>
            </form>
        </div>
        <div id="fcf-thank-you" style="display:none">
            <p>' . $lang['success'] . '</p>
        </div>
    </div>';

    Action::add('head', function () use ($urlAction) {
        echo '<link href="' . $urlAction . '/fcf-assets/css/fcf.default.css" rel="stylesheet">';
        echo '<style rel="stylesheet">.fcf-form-wrap{max-width:100%;padding:0;border-radius:0;background-color:var(--light-50)}#fcf-form{background-color:var(--light-50);color:var(--dark-25)}#fcf-thank-you{color:var(--dark-25)}.label{color:var(--dark-25)}strong{color:var(--dark-25)}.input,.textarea,.select select{background-color:var(--light-25);border-color:var(--light);color:var(--dark-25)}.input:focus,.textarea:focus,.select select:focus,.input:active,.textarea:active,.select select:active,.input:hover,.textarea:hover,.select select:hover{border-color:var(--dark-50);box-shadow:none}.file-label:hover .file-cta{background-color:var(--dark-50);color:var(--light)}.file-label:hover .file-name{border-color:var(--light)}.file-label:active .file-cta{background-color:var(--dark-50);color:var(--light-50)}.file-label:active .file-name{border-color:var(--dark)}.file-cta{background-color:var(--dark-50);border-color:var(--dark);color:var(--light-50)}.file-name{background-color:var(--dark-50);border-color:var(--light-25)}.button.is-link{background-color:var(--dark);border-color:var(--light-25)}.button.is-link:hover{background-color:var(--dark-25);border-color:var(--light-25)}.button.is-link[disabled]{background-color:var(--dark-25);border-color:transparent;box-shadow:none}.fcf-attribution{color:var(--light);text-align:right}.fcf-attribution-link{color:var(--dark-50)}</style>';
    });
    Action::add('footer', function () use ($urlAction) {
        echo '<script src="' . $urlAction . '/fcf-assets/js/fcf.just-validate.min.js"></script>';
        echo '<script src="' . $urlAction . '/fcf-assets/js/fcf.form.js"></script>';
    });
    return $html;
});
