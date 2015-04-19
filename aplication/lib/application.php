<?php
/**
 * Main application class
 */

/**
 * This class handles the client request
 */
class Application {
    const EMAIL_KEY = 'email';


    public function run(){
        session_start();
        // http auth
        if ($this->_auth()) {
            // if e-mail isset in session show upload file form
            if (isset($_SESSION[self::EMAIL_KEY])) {
                // show upload file form
                $this->_showUploadFileForm();
            } else {
                // if e-mail is not set in session show e-mail form

                // if form was submitted check email
                if (count($_POST)) {
                    // validate email
                    if ($this->_validateEmail($_POST['email'])) {
                        $_SESSION[self::EMAIL_KEY] = $_POST['email'];
                    }
                } else {
                    var_dump($_SESSION);
                    // show email form
                    $this->_showEmailForm();
                }
            }
        }
    }

    /**
     * Authenticates user
     *
     * @return bool
     */
    private function _auth(){
        global $SETTINGS;

        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="FFmpeg"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'You must authenticate';
        } else {
            if ($_SERVER['PHP_AUTH_USER'] == $SETTINGS['user'] && $_SERVER['PHP_AUTH_PW'] == $SETTINGS['password']) {

                return true;
            } else {
                echo 'Invalid user or password. Clear active logins and try again.';
            }
        }

        return false;
    }

    private function _showEmailForm() {
        //require '';
        require 'emailForm.php';
        //require '';
    }

    private function _showUploadFileForm() {
        //require '';
        require 'uploadFileForm.php';
        //require '';
    }

    private function _validateEmail($e){
        if (filter_var($e, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        else {
            return flase;
        }
    }
}
