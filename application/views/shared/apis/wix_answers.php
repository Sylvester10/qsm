<script>
	(function(){
		var w=window;
		function l(){
		 	var d=document;
		 	var aws=d.createElement('script');
		 	aws.type='text/javascript';
		 	aws.async=true;
		 	aws.src='https://quickschoolmanager.wixanswers.com/apps/widget/v1/quickschoolmanager/0df73c88-151f-4cd9-b451-80f78df5ad5e/en/embed.js';

		 	aws.onload = aws.onreadystatechange = function() {
		    	var rs = this.readyState;
		     	if (rs && rs != 'complete' && rs != 'loaded') return;
		   		//   try {

		   		//   } catch (e) {}
		 	};
		 	var s=d.getElementsByTagName('script')[0];
		 	s.parentNode.insertBefore(aws,s);
		}
		if(w.addEventListener){
		 	w.addEventListener('load',l);
		}else{
		 	w.attachEvent('onload',l);
		}
	})()
</script>