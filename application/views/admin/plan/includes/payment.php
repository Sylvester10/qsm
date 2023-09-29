
<form>
    <script src="<?php echo $script_url; ?>"></script>	
    <button type="button" class="btn btn-success btn-lg" onClick="payWithRave()">Pay Now</button>
</form>



<script>
	function payWithRave() {
        getpaidSetup({
            PBFPubKey: "<?php echo $public_key; ?>",
			amount: "<?php echo $amount; ?>",
            currency: "<?php echo $currency; ?>", //NGN or USD depending on user's location
            customer_email: "<?php echo $email; ?>",
			customer_firstname: "<?php echo $first_name; ?>",
            customer_phone: "<?php echo $phone; ?>",
            payment_method: "<?php echo $payment_method; ?>",
            txref: "<?php echo $tranx_ref; ?>",
            integrity_hash: "<?php echo $integrity_hash; ?>",
            meta: [{
				metaname: "School_ID", metavalue: "<?php echo $school_id; ?>"
			}],
            onclose: function() {},
            callback:function(response) {
				//process callback function using rave response values 
				process_payment(response.tx.txRef, response.tx.vbvrespmessage, response.tx.status, response.tx.raveRef, response.tx.updatedAt, response.tx.amount);
			}   
        });
    }
	
	
	function process_payment(tranx_ref, msg, status, rave_ref, date_updated, amount) {
		$.ajax ({
			type: "POST",
			url: "<?php echo  base_url('rave/process_payment'); ?>",
			data: {tranx_ref, msg, status, rave_ref, date_updated, amount},
			success: function (res) {
				console.log(res); 
				window.location.href = "<?php echo base_url('admin'); ?>";
			}
		});
	}
</script>