
<!--Chart Plugins-->	
<script src="<?php echo base_url(); ?>assets/vendors/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/echarts/dist/echarts.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/raphael/raphael.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendors/morris.js/morris.min.js"></script>		


<script>

	
	//Pie Chart
	var myChart = echarts.init(document.getElementById('broadsheet_pie_chart'));
	var option = {
	    title : {
	        text: 'Final Grade Distribution',
	        subtext: '',
	        x:'center'
	    },
	    animation: false,
	    tooltip : {
	        trigger: 'item',
	        formatter: "{a} <br/>{b} : {c} ({d}%)"
	    },
	    legend: {
			orient : 'horizontal',
			x: 'center',
			top: 30,
	        data: [<?php echo $grade_range_string; ?>]
	    },
	    series : [
	        {
	            name: '',
	            type: 'pie',
	            radius : '55%',
	            center: ['50%', '60%'],
	            data: [<?php echo $pie_chart_data_string; ?>],
	            itemStyle: {
	                emphasis: {
	                    shadowBlur: 10,
	                    shadowOffsetX: 0,
	                    shadowColor: 'rgba(0, 0, 0, 0.5)'
	                }
	            }
	        }
	    ]
	};
	// use configuration item and data specified to show chart
    myChart.setOption(option);



    //Line Chart
	var broadsheet_line_chart = Morris.Line({
		// ID of the element in which to draw the chart.
		element: 'broadsheet_line_chart',
		// The name of the data record attribute that contains x-values.
		xkey: 'subject',
		parseTime: false,
		xLabelAngle: 45, //in degrees
		// A list of names of data record attributes that contain y-values.
		ykeys: ['class_average', 'class_min', 'class_max'],
		// Labels for the ykeys -- will be displayed when you hover over the
		// chart.
		labels: ['Class Ave Score', 'Class Min Score', 'Class Max Score'],
		lineColors: ['#373651', '#E65A26', '#26B99A'],
		hideHover: 'auto', //hide hover legend when cursor is outside of chart
		// Chart data records -- each entry in this array corresponds to a point on the chart.
		//data format: { subject: 'English Language', class_average: '90.3', class_min: 74, class_max: 100 },
		data: [<?php echo $line_chart_data_string; ?>],
	});


</script>
