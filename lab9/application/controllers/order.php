<?php

/**
 * Order handler
 * 
 * Implement the different order handling usecases.
 * 
 * controllers/welcome.php
 *
 * ------------------------------------------------------------------------
 */
class Order extends Application {

    function __construct() {
        parent::__construct();
    }

    // start a new order
    function neworder() {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }

        $order_num = $this->orders->highest() + 1;

        $record = $this->orders->create();
        $record->num = $order_num;
        $record->date = date(DATE_ATOM);
        $record->status = 'a';
        $record->total = 0.0;
        $this->orders->add($record);

        redirect('/order/display_menu/' . $order_num);
    }

    // add to an order
    function display_menu($order_num = null) {
        $title = 'Menu';
        $this->data['checkout'] = "";
        $this->data['order_num'] = "0";

        if ($this->ion_auth->logged_in())
        {
            if ($order_num == null)
                redirect('/order/neworder');
            $this->data['order_num'] = $order_num;
            $title = 'Order #' . $order_num . ' (' . number_format($this->orders->total($order_num), 2) . ')';
            $this->data['checkout'] = 'Click on a menu item below to add it to your order, or<a href="/order/checkout/{order_num}" class="btn btn-primary">Checkout</a>';
        }

        $this->data['pagebody'] = 'show_menu';

        // get order details for title
        $this->data['title'] = $title;

        // Make the columns
        $this->data['meals'] = $this->make_column('m');
        $this->data['drinks'] = $this->make_column('d');
        $this->data['sweets'] = $this->make_column('s');

        $this->render();
    }

    // make a menu ordering column
    function make_column($category) {
        $items = $this->menu->some('category', $category);
        return $items;
    }

    // add an item to an order
    function add($order_num, $item) {
        $this->orders->add_item($order_num, $item);
        redirect('/order/display_menu/' . $order_num);
    }

    // checkout
    function checkout($order_num) {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }

        $this->data['title'] = 'Checking Out';
        $this->data['pagebody'] = 'show_order';
        $this->data['order_num'] = $order_num;
        $this->data['total'] = number_format($this->orders->total($order_num), 2);

        $items = $this->orderitems->group($order_num);
        foreach ($items as $item) {
            $menuitem = $this->menu->get($item->item);
            $item->code = $menuitem->name;
        }
        $this->data['items'] = $items;

        $this->data['okornot'] = $this->orders->validate($order_num) ? "" : "disabled";

        $this->render();
    }

    // proceed with checkout
    function commit($order_num) {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }

        if (!$this->orders->validate($order_num))
            redirect('/order/display_menu/' . $order_num);
        $record = $this->orders->get($order_num);
        $record->date = date(DATE_ATOM);
        $record->status = 'c';
        $record->total = $this->orders->total($order_num);
        $this->orders->update($record);
        redirect('/');
    }

    // cancel the order
    function cancel($order_num) {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login', 'refresh');
        }

        $this->orderitems->delete_some($order_num);
        $record = $this->orders->get($order_num);
        $record->status = 'x';
        $this->orders->update($record);
        redirect('/');
    }

}

/* End of file welcome.php */
/* Location: application/controllers/welcome.php */
