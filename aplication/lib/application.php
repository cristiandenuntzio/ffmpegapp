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
        // http auth
        if ($this->_auth()) {
            if (
                isset($_POST['email']) && $this->_validateEmail($_POST['email']) &&
                isset($_FILES['upload']['error']) && $_FILES['upload']['error'] == UPLOAD_ERR_OK &&
                isset($_FILES['upload']['tmp_name']) && is_file($_FILES['upload']['tmp_name'])
            ) {
               //call move upload file, generate file name

               //add new entry in data base -> file name, email, status

               //call processor

            // if e-mail isset and validate show upload file form
            } else if (isset($_POST['email']) && $this->_validateEmail($_POST['email'])) {
                // show upload file form
                $this->_showUploadFileForm(array('email' => $_POST['email']));
            } else {
                // show email form
                $this->_showEmailForm();
            }
        }
    }

    public function getBasePath(){
        $basePath = realpath(dirname(__FILE__) . '../../');

        return $basePath;
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

    private function _showEmailForm(){
        $this->_renderView('/views/emailForm.php');
    }

    private function _showUploadFileForm($vars){
        $this->_renderView('/views/uploadFileForm.php', $vars);
    }

    private function _validateEmail($e){
        if (filter_var($e, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        else {
            return false;
        }
    }

    private function _renderView($view, $vars = array()){
        foreach ($vars as $key => $value) {
            $$key = $value;
        }

        require $this->getBasePath() . '/views/header.php';
        require $this->getBasePath() . $view;
        require $this->getBasePath() . '/views/footer.php';
    }


}
