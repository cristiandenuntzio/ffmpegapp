<?php
/**
 * Handles notification between client and server
 */

class Messenger
{
    public function notify() {
        global $SETTINGS;
        // create socket
        $socket = socket_create(AF_INET, SOCK_STREAM, 0);

        if (!$socket) {
            $errorNo = socket_last_error();
            $errorName = socket_strerror($errorNo);

            die("Unable to create socket. Error: $errorNo: $errorName");
        }

        // set blocking
        socket_set_block($socket) or die("Unable to set block on socket");

        // connect - blocking
        $connect = socket_connect($socket, $SETTINGS['hostAddress'], $SETTINGS['hostPort']);

        if (!$connect) {
            $errorNo = socket_last_error();
            $errorName = socket_strerror($errorNo);

            die("Unable to connect. Error: $errorNo: $errorName");
        }

        // disconnect
        socket_close($socket);
    }

    public function onNotification(callable $callback) {
        global $SETTINGS;
        // create socket
        $socket = socket_create(AF_INET, SOCK_STREAM, 0);

        if (!$socket) {
            $errorNo = socket_last_error();
            $errorName = socket_strerror($errorNo);

            die("Unable to create socket. Error: $errorNo: $errorName");
        }

        // set blocking
        socket_set_block($socket) or die("Unable to set block on socket");

        // bind socket
        $socketBind = socket_bind($socket, $SETTINGS['hostAddress'], $SETTINGS['hostPort']);

        if (!$socketBind) {
            $errorNo = socket_last_error();
            $errorName = socket_strerror($errorNo);

            die("Unable to bind socket. Error: $errorNo: $errorName");
        }

        // start listening
        $socketListen = socket_listen($socket);

        if (!$socketListen) {
            $errorNo = socket_last_error();
            $errorName = socket_strerror($errorNo);

            die("Unable to start listening on socket. Error: $errorNo: $errorName");
        }

        // start main loop
        while (TRUE) {
            // accept socket - blocking
            $acceptedSocket = socket_accept($socket);

            if (!$acceptedSocket) {
                $errorNo = socket_last_error();
                $errorName = socket_strerror($errorNo);

                die("Unable to accept socket. Error: $errorNo: $errorName");
            }
            // close accepted socket
            socket_close($acceptedSocket);
            // call callback
            $callback();

            // end main loop
        }
    }

}
