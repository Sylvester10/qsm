
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<div class="row">
		<div class="col-md-6">
			<div class="new-item">
				<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('school_library_admin/new_book'); ?>"><i class="fa fa-plus"></i> New Book</a>
				<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('school_library_admin/borrowed_books'); ?>"><i class="fa fa-book"></i> Borrowed Books</a>
			</div>
		</div>
		<div class="col-md-6">
			<div class="pull-right m-t-10 m-b-10">
				<h4 class="text-bold">Books Information</h4>
				<p>Total Unique Books: <?php echo $total_unique_books; ?></p>
				<p>Total Books (with copies): <?php echo $total_books_copies; ?></p>
				<p>Available Books (with copies): <?php echo $total_available_copies; ?></p>
				<p>Borrowed Books (with copies): <?php echo $total_borrowed_copies; ?></p>
				<p>Borrowers: <?php echo $total_borrowers; ?></p>
			</div>
		</div>
	</div>

	<?php
	if ($total_due_books > 0) { ?>
		<div class="alert alert-danger alert-dismissable text-center">
			<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>
			<?php echo $total_due_books; ?> combined copies of <?php echo $due_books; ?> borrowed <?php echo ($due_books == 1) ? 'book' : 'books'; ?> are overdue for return. 
		</div>
	<?php } ?>

	<?php 
	//select options bulk actions 
	$options_array = array(
		//'value' => 'Caption'
		'delete' => 'Delete'
	); 
	echo modal_bulk_actions('school_library_admin/bulk_actions_books', $options_array); ?>
	
	<table id="all_books_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
		
		<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
		
		<thead>
			<tr>
				<th class="w-15-p"> <input type="checkbox" class="radio-box select_all" /> </th>
				<th> Actions </th>
				<th class="min-w-250"> Book Title </th>
				<th class="min-w-200"> Author </th>
				<th class="min-w-150"> ISBN Number </th>
				<th class="min-w-100"> Edition</th>
				<th class="min-w-150"> Copies Lent Out </th>
				<th class="min-w-150"> Copies Available </th>
				<th class="min-w-150"> Total Copies in Stock</th>
				<th class="min-w-150"> Date Added </th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	
<?php echo form_close(); ?>