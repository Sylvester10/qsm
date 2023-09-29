<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Paypal_model extends CI_Model{
    
    public function __construct() {
        $this->tranx_table = 'paypal_transactions';
		$this->paypal_currency_code = $this->config->item('paypal_lib_currency_code'); 
    }
    
   	
    
    public function insert_transaction_details($transaction_data) {
		$data = array(
			'school_id'      	=> $transaction_data["custom"],
			'item_name'         => $transaction_data["item_name"], //what user is paying for
			'txn_id'         	=> $transaction_data["txn_id"],
			'amount'    		=> $transaction_data["mc_gross"],
			'currency_code'   	=> $transaction_data["mc_currency"],
			'payer_email'    	=> $transaction_data["payer_email"],
			'payment_status' 	=> $transaction_data["payment_status"],
		);
        $insert = $this->db->insert($this->tranx_table, $data);
		return $insert ? TRUE : FALSE;
    }
	
	
	public function get_transaction_details($txn_id) {
		return $this->db->get_where($this->tranx_table, array('txn_id' => $txn_id))->row();
	}
	
	
	public function email_receipt($transaction_data) {
		$school_id = $transaction_data['custom']; //custom message
		$school_name = $this->common_model->get_school_info($school_id)->school_name;
		
		$payer_email = $transaction_data["payer_email"];
		$subject = 'Receipt of Payment via PayPal';	
		$message = 	'Here is a receipt of your payment via PayPal: <br />
					School Name: ' . $school_name . '<br />
					Payment for: ' . $transaction_data["item_name"] . '<br />
					Amount Paid: $' . $transaction_data["mc_gross"] . ' ' . $this->paypal_currency_code . '<br />
					Transaction ID: ' . $transaction_data["txn_id"] . '<br />
					Payment Status: ' . $transaction_data["payment_status"] . '<br /><br />
					
					<p class="success"> You may log into your account at <a href="https://www.paypal.com" target="_blank">www.paypal.com</a> to view details of this transaction. </p>'; 
		
		//email payer
		email_user_default($payer_email, $subject, $message);
		
		//email sample of receipt to developer
		$developer_email = developer_mail;
		return email_user_default($developer_email, $subject, $message);
	}
	
	
	public function email_developer($message) {
		$developer_email = developer_mail;
		$subject = 'PayPal IPN Verification';
		return email_user_default($developer_email, $subject, $message);
	}
    
}