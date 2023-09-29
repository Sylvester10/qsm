<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * PayPal Library for CodeIgniter 3.x
 *
 * Library for PayPal payment gateway. It helps to integrate PayPal payment gateway
 * in the CodeIgniter application.
 *
 * It requires PayPal configuration file and it should be placed in the config directory.
 *
 * @package     	CodeIgniter
 * @category    	Libraries
 * @Contributor     Nwankwo Ikemefuna
 */

class Paypal_lib {

    var $fields = array();
    var $submit_btn = '';
    var $button_path = '';
    var $CI;
    
    function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->helper('url');
        $this->CI->load->helper('form');
        $this->CI->load->config('paypal');
        
        $this->sandbox = $this->CI->config->item('sandbox');              
        $this->paypal_url = $this->CI->config->item('paypal_url');              
        $this->button_path = $this->CI->config->item('paypal_lib_button_path');
        
        // populate $fields array with a few default values.
        $this->business_email = $this->CI->config->item('business');
        $this->add_field('business', $this->business_email);
        $this->add_field('rm', '2'); //return method (1 = GET, 2 = POST). Default is 2 if IPN is enabled and points to script
        $this->add_field('cmd', '_xclick'); //x_click (Buy Now), _cart(for shopping cart purposes), _donations(Donate), etc
		
		if ($this->sandbox == TRUE) $this->add_field('test_ipn', 1); //IPN testing message for sandbox only
        $this->button('Pay Now!');
    }
	

    function button($value) {
        // changes the default caption of the submit button
        $this->submit_btn = form_submit('pp_submit', $value);
    }
	

    function image($file) {
        $this->submit_btn = '<input type="image" name="add" src="'.base_url(rtrim($this->button_path, '/').'/'. $file).'" border="0" />';
    }
	

    function add_field($field, $value) {
        // adds a key=>value pair to the fields array
        $this->fields[$field] = $value;
    }
	

    function paypal_auto_form() {
        // form with hidden elements which is submitted to paypal
        $this->button('Click here if you\'re not automatically redirected...');
        echo '<html>' . "\n";
        echo '<head><title>Processing Payment...</title></head>' . "\n";
        echo '<body style="text-align:center;" onLoad="document.forms[\'paypal_auto_form\'].submit();">' . "\n";
        echo '<p style="text-align:center;">Please wait, your order is being processed and you will be redirected to the PayPal website.</p>' . "\n";
        echo $this->paypal_form('paypal_auto_form');
        echo '</body></html>';
    } 
	

    function paypal_form($form_name = 'paypal_form') {
		$str = '';
        $str .= '<form method="post" action="'.$this->paypal_url.'" name="'.$form_name.'" />' . "\n";
		foreach ($this->fields as $name => $value) {
            $str .= form_hidden($name, $value) . "\n";
		}
        $str .= '<p>'. $this->submit_btn . '</p>';
        $str .= form_close() . "\n";
        return $str;
    }
	
     

}