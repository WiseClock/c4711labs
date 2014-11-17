<?php

class AddAccounts extends Application {

    function __construct() {
        parent::__construct();
    }

    //-------------------------------------------------------------
    //  The normal pages
    //-------------------------------------------------------------

    function index() {
        $this->data['title'] = 'Add Accounts';

        $this->ion_auth->register('donald', 'duck', 'donald@admin.com', array(), array(1,2)); //admin + user
        $this->ion_auth->register('mickey', 'mouse', 'mickey@user.com', array(), array(2)); //user

        echo "FINISHED!<br />";
    }

}
