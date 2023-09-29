

	<table class="table table-bordered table-hover cell-text-middle" style="text-align: left">
		<thead>
			<tr>
				<th class="w-5"> S/N </th>
				<th class="w-25"> Table Name </th>
				<th class="w-15"> Total Rows </th>
				<th class="w-15"> Total Columns </th>
				<th class="w-15"> Has School ID </th>
			</tr>
		</thead>
		<tbody>
		
			<?php 
			$count = 1;
			$tables = $this->db->list_tables();

			foreach ($tables as $table) {
				
				$total_rows = $this->db->get_where($table)->num_rows();
				$fields = $this->db->list_fields($table);
				$total_columns = count($fields);
				if ($this->db->field_exists('school_id', $table)) {
					$school_id = 'Yes';
				} else {
					$school_id = 'No';
				} ?>

				<tr>
					<td><?php echo $count; ?></td>
					<td><?php echo humanize($table); ?></td>
					<td><?php echo $total_rows; ?></td>
					<td><?php echo $total_columns; ?></td>
					<td><?php echo $school_id; ?></td>
				</tr>
					
			   	<?php $count++; 

			} ?>

		</tbody>
	</table>