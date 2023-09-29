
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>



<h3 class="text-bold">Step 1: Import Data</h3>


<?php 
if ($total_imported_staff > 0) { 
	$imports = ($total_imported_staff == 1) ? 'import' : 'imports'; ?>

	<div class="alert alert-danger text-center text-bold f-s-17" style="color: #fff;">
		<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>
		You have <?php echo $total_imported_staff; ?> uncompleted staff <?php echo $imports; ?>. To avoid duplication, you will not be able to import new staff data until you have actioned outstanding imports. 
	</div>	 
 
	<a class="" href="<?php echo base_url('staff_import/imported_staff'); ?>">View Outstanding Imports </a>

<?php } ?>


<div class="row">

	<div class="col-md-9 table-scroll">


		<h3>Import Instructions</h3>
		<ul class="adjust-list-left">
			<li>Prepare staff data in Excel spreadsheet. 
				<a class="underline-link" href="<?php echo base_url('assets/uploads/imports/template/StaffRecord.xlsx'); ?>">Download Template</a>
			</li>
			<li>Ensure only one worksheet exists in workbook.</li>
			<li>Type the titles on ROW 1 of worksheet.</li>
			<li>Enter the data, starting from ROW 2.</li>
			<li>Ensure consecutive rows have data to avoid importing empty rows.</li>
			<li>Organize the staff data in the format below, making sure to follow stipulated rules.</li>
		</ul>


		<div class="">
			<h3>Format of Excel Worksheet</h3>
			<table class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			
				<thead>
					<tr>
						<th class="w-5"> Column </th>
						<th class="w-25"> Data </th>
						<th class="w-20"> Rule(s) </th>
						<th class="w-35"> Additional Information </th>
						<th class="w-15"> Example </th>
					</tr>
				</thead>
				
				<tbody>
				
					<tr>
						<td>A</td>
						<td>Title</td>
						<td>Optional</td>
						<td></td>
						<td>Mr.</td>
					</tr>
					
					<tr>
						<td>B</td>
						<td>Name <small>(Surname first)</small></td>
						<td>Required</td>
						<td></td>
						<td>Doe Chris Anderson</td>
					</tr>
					
					<tr>
						<td>C</td>
						<td>Email</td>
						<td>Required</td>
						<td></td>
						<td>chrisdoe@gmail.com</td>
					</tr>
					
					<tr>
						<td>D</td>
						<td>Mobile No.</td>
						<td>Required</td>
						<td></td>
						<td>07080000002</td>
					</tr>
					
					<tr>
						<td>E</td>
						<td>Nationality</td>
						<td>Required</td>
						<td></td>
						<td>South Africa</td>
					</tr>
					
					<tr>
						<td>F</td>
						<td>State of Origin</td>
						<td>Optional</td>
						<td></td>
						<td></td>
					</tr>
					
					<tr>
						<td>G</td>
						<td>LGA of Origin</td>
						<td>Optional</td>
						<td></td>
						<td></td>
					</tr>
					
					<tr>
						<td>H</td>
						<td>Designation</td>
						<td>Required</td>
						<td>Particularly, ensure a class teacher is given the designation <b>Class Teacher</b> so that they appear in the list of staff to be assigned to classes. </td>
						<td>Class Teacher</td>
					</tr>
					
					<tr>
						<td>I</td>
						<td>Date of Birth (DOB)</td>
						<td>Required</td>
						<td>Enter DOB in the format: YYYY/MM/DD.</td>
						<td>2001/08/26</td>
					</tr>
					
					<tr>
						<td>J</td>
						<td>Sex</td>
						<td>Required</td>
						<td>Male or Female</td>
						<td>Female</td>
					</tr>
					
					<tr>
						<td>K</td>
						<td>Residential Address</td>
						<td>Optional</td>
						<td></td>
						<td>54 Bukola Avenue, Apapa, Lagos</td>
					</tr>
					
					<tr>
						<td>L</td>
						<td>Religion</td>
						<td>Optional</td>
						<td></td>
						<td>Christianity</td>
					</tr>
					
					<tr>
						<td>M</td>
						<td>Highest Educational Qualification</td>
						<td>Optional</td>
						<td>First School Leaving Certificate, SSCE, NCE, OND/HND, First Degree, Masters, PhD</td>
						<td>First Degree</td>
					</tr>
					
					<tr>
						<td>N</td>
						<td>Date Employed</td>
						<td>Optional</td>
						<td>Enter DOB in the format: YYYY/MM/DD.</td>
						<td>2011/10/03</td>
					</tr>
					
					<tr>
						<td>O</td>
						<td>Next of Kin</td>
						<td>Optional</td>
						<td></td>
						<td>Mr. Peter Doe</td>
					</tr>
					
					<tr>
						<td>P</td>
						<td>Next of Kin Email</td>
						<td>Optional</td>
						<td></td>
						<td>nextkin@gmail.com</td>
					</tr>
					
					<tr>
						<td>Q</td>
						<td>Next of Kin Mobile No.</td>
						<td>Optional</td>
						<td></td>
						<td>09012345678</td>
					</tr>
					
					<tr>
						<td>R</td>
						<td>Next of Kin Home Address</td>
						<td>Optional</td>
						<td></td>
						<td></td>
					</tr>
					
					<tr>
						<td>S</td>
						<td>Bank Account No.</td>
						<td>Optional</td>
						<td></td>
						<td></td>
					</tr>
					
					<tr>
						<td>T</td>
						<td>Bank Name</td>
						<td>Optional</td>
						<td></td>
						<td></td>
					</tr>
					
					<tr>
						<td>U</td>
						<td>Additional Information</td>
						<td>Optional</td>
						<td>Any extra information not captured in the fields above</td>
						<td></td>
					</tr>
					
				</tbody>
				
			</table>
		</div><!--/.table-scroll-->

	</div><!--/.col-->


	<div class="col-md-3">
		<?php echo form_open_multipart('staff_import/import_staff_action'); ?>
			<div class="form-group">
				<h3>Import Records</h3>
				<small>Only Excel files allowed (max. 5MB)</small>
				<input type="file" name="excel_file" class="form-control" accept=".xls,.xlsx" required />
			</div>

			<div class="form-error"><?php echo $upload_error['error']; ?></div>
			
			<div class="form-group">
				<?php 
				//assign disabled attribute to import button if there is outstanding import
				$disabled = ($total_imported_staff > 0) ? 'disabled' : NULL; ?>
				<button class="btn btn-primary btn-lg" <?php echo $disabled; ?> >Import</button>
			</div>

		<?php echo form_close(); ?>

		<div class="f-s-17 m-t-30 text-bold">Note</div>
		In step 2, you can review imported staff data before submitting to staff list. 

	</div>

</div><!--/.row-->