<?php

/**
 * Our homepage.
 * 
 * Present a summary of the completed orders.
 * 
 * controllers/welcome.php
 *
 * ------------------------------------------------------------------------
 */
class Welcome extends Application {

    function __construct() {
        parent::__construct();
    }

    //-------------------------------------------------------------
    //  The normal pages
    //-------------------------------------------------------------

    function index() {
        $this->data['authenbtn'] = '<a href="/auth/login" class="btn btn-large btn-default">Login</a>';
        if ($this->ion_auth->logged_in())
        {
            $this->data['authenbtn'] = '<a href="/auth/logout" class="btn btn-large btn-default">Logout</a>';
        }

        $this->data['title'] = 'Jim\'s Joint!';
        $this->data['pagebody'] = 'welcome';

        // Get all the completed orders
        $completed = $this->orders->some('status','c');

        // Build a multi-dimensional array for reporting
        $orders = array();
        foreach ($completed as $order) {
            $this1 = array(
                'num' => $order->num,
                'datetime' => $order->date,
                'amount' => $order->total
            );
            $orders[] = $this1;
        }

        // and pass these on to the view
        $this->data['orders'] = $orders;
        
        $this->render();
    }

}

/* End of file welcome.php */
/* Location: application/controllers/welcome.php */
