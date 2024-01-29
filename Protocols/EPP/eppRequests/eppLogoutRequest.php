<?php
namespace Metaregistrar\EPP;

/*
 * This object contains all the logic to create an EPP hello command
 */

class eppLogoutRequest extends eppRequest {
    function __construct() {
        parent::__construct();
        #
        # Create logout command
        #
        $logout = $this->createElement('logout');
        $this->getCommand()->appendChild($logout);
        $this->addSessionId();
    }

    function __destruct() {

    }

}
