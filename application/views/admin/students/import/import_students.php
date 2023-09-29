
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>



<h3 class="text-bold">Step 1: Import Data</h3>


<?php 
if ($total_imported_students > 0) { 
	$imports = ($total_imported_students == 1) ? 'import' : 'imports'; ?>

	<div class="alert alert-danger text-center text-bold f-s-17" style="color: #fff;">
		<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>
		You have <?php echo $total_imported_students; ?> uncompleted student <?php echo $imports; ?>. To avoid duplication, you will not be able to import new student data until you have actioned outstanding imports. 
	</div>	 
 
	<a class="" href="<?php echo base_url('student_import/imported_students'); ?>">View Outstanding Imports </a>

<?php } ?>


<div class="row">

	<div class="col-md-9 table-scroll">


		<h3>Import Instructions</h3>
		<ul class="adjust-list-left">
			<li>Prepare student data in Excel spreadsheet. 
				<a class="underline-link" href="<?php echo base_url('assets/uploads/imports/template/StudentsRecord.xlsx'); ?>">Download Template</a>
			</li>
			<li>Ensure only one worksheet exists in workbook.</li>
			<li>Type the titles on ROW 1 of worksheet.</li>
			<li>Enter the data, starting from ROW 2.</li>
			<li>Ensure consecutive rows have data to avoid importing empty rows.</li>
			<li>Organize the student data in the format below, making sure to follow stipulated rules.</li>
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
						<td>Admission ID</td>
						<td>Required</td>
						<td></td>
						<td>SPF/2018/0367</td>
					</tr>
					
					<tr>
						<td>B</td>
						<td>Last Name</td>
						<td>Required</td>
						<td></td>
						<td>Benson</td>
					</tr>
					
					<tr>
						<td>C</td>
						<td>First Name</td>
						<td>Required</td>
						<td></td>
						<td>David</td>
					</tr>
					
					<tr>
						<td>D</td>
						<td>Other Name</td>
						<td>Optional</td>
						<td></td>
						<td>Adekunle</td>
					</tr>
					
					<tr>
						<td>E</td>
						<td>Class ID</td>
						<td>Required, Integer</td>
						<td>Important: Class ID of student, not class name. See <a class="text-primary underline-link" href="<?php echo base_url('admin/classes'); ?>" target="_blank">classes</a> for a list of your school's classes and their corresponding class IDs.</td>
						<td>39</td>
					</tr>
					
					<tr>
						<td>F</td>
						<td>Date of Birth (DOB)</td>
						<td>Required</td>
						<td>Enter DOB in the format: YYYY/MM/DD.</td>
						<td>2001/08/26</td>
					</tr>
					
					<tr>
						<td>G</td>
						<td>Sex</td>
						<td>Required</td>
						<td>Male or Female</td>
						<td>Female</td>
					</tr>
					
					<tr>
						<td>H</td>
						<td>Blood Group</td>
						<td>Optional</td>
						<td>A, AB, B, O<sup>+</sup>, O<sup>-</sup> </td>
						<td>AB</td>
					</tr>
					
					<tr>
						<td>I</td>
						<td>Place of Birth</td>
						<td>Optional</td>
						<td></td>
						<td>Port Harcourt</td>
					</tr>
					
					<tr>
						<td>J</td>
						<td>Nationality</td>
						<td>Required</td>
						<td></td>
						<td>Nigeria</td>
					</tr>
					
					<tr>
						<td>K</td>
						<td>State of Origin</td>
						<td>Optional</td>
						<td></td>
						<td>Lagos</td>
					</tr>
					
					<tr>
						<td>L</td>
						<td>LGA of Origin</td>
						<td>Optional</td>
						<td></td>
						<td>Surulere</td>
					</tr>
					
					<tr>
						<td>M</td>
						<td>Hometown</td>
						<td>Optional</td>
						<td>Student's hometown/village</td>
						<td>Surulere</td>
					</tr>
					
					<tr>
						<td>N</td>
						<td>Home Address</td>
						<td>Optional</td>
						<td>Student's residential address</td>
						<td>18 Randle Avenue, Surulere, Lagos</td>
					</tr>
					
					<tr>
						<td>O</td>
						<td>Religion</td>
						<td>Optional</td>
						<td></td>
						<td>Christianity</td>
					</tr>
					
					<tr>
						<td>P</td>
						<td>Former School</td>
						<td>Optional</td>
						<td>Student's former school prior to seeking admission in your school.</td>
						<td>Tadwell College</td>
					</tr>
					
					<tr>
						<td>Q</td>
						<td>Former School Address</td>
						<td>Optional</td>
						<td></td>
						<td>74 Sowewimo Street, Ikoyi, Lagos</td>
					</tr>
					
					<tr>
						<td>R</td>
						<td>Last Class Former School</td>
						<td>Optional</td>
						<td></td>
						<td>Grade 5</td>
					</tr>
					
					<tr>
						<td>S</td>
						<td>First Parent/Guardian Name</td>
						<td>Required</td>
						<td></td>
						<td>Mr. Paul Benson</td>
					</tr>
					
					<tr>
						<td>T</td>
						<td>First Parent/Guardian Sex</td>
						<td>Optional</td>
						<td>Male or Female</td>
						<td>Male</td>
					</tr>
					
					<tr>
						<td>U</td>
						<td>First Parent/Guardian R/ship</td>
						<td>Optional</td>
						<td>Relationship of first parent to student</td>
						<td>Father</td>
					</tr>
					
					<tr>
						<td>V</td>
						<td>First Parent/Guardian Phone No.</td>
						<td>Optional</td>
						<td></td>
						<td>07068953225</td>
					</tr>
					
					<tr>
						<td>W</td>
						<td>First Parent/Guardian Email</td>
						<td>Optional but must be specified if parent login is desired.</td>
						<td>Students of the same parent/guardian should have the same email address</td>
						<td>paulbenson@gmail.com</td>
					</tr>
					
					<tr>
						<td>X</td>
						<td>Second Parent/Guardian Name</td>
						<td>Optional</td>
						<td></td>
						<td>Mrs. Angela Benson</td>
					</tr>
					
					<tr>
						<td>Y</td>
						<td>Second Parent/Guardian Sex</td>
						<td>Optional</td>
						<td>Male or Female</td>
						<td>Female</td>
					</tr>
					
					<tr>
						<td>Z</td>
						<td>Second Parent/Guardian R/ship</td>
						<td>Optional</td>
						<td>Relationship of second parent to student</td>
						<td>Mother</td>
					</tr>
					
					<tr>
						<td>AA</td>
						<td>Second Parent/Guardian Phone No.</td>
						<td>Optional</td>
						<td></td>
						<td>07068953226</td>
					</tr>
					
					<tr>
						<td>AB</td>
						<td>Second Parent/Guardian Email</td>
						<td>Optional</td>
						<td></td>
						<td>angelabenson@gmail.com</td>
					</tr>
					
					<tr>
						<td>AC</td>
						<td>Admission Date</td>
						<td>Optional</td>
						<td></td>
						<td></td>
					</tr>
					
					<tr>
						<td>AD</td>
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
		<?php echo form_open_multipart('student_import/import_students_action'); ?>
			<div class="form-group">
				<h3>Import Records</h3>
				<small>Only Excel files allowed (max. 5MB)</small>
				<input type="file" name="excel_file" class="form-control" accept=".xls,.xlsx" required />
			</div>

			<div class="form-error"><?php echo $upload_error['error']; ?></div>
			
			<div class="form-group">
				<?php 
				//assign disabled attribute to import button if there is outstanding import
				$disabled = ($total_imported_students > 0) ? 'disabled' : NULL; ?>
				<button class="btn btn-primary btn-lg" <?php echo $disabled; ?> >Import</button>
			</div>

		<?php echo form_close(); ?>

		<div class="f-s-17 m-t-30 text-bold">Note</div>
		In step 2, you can review imported student data before submitting to students list. 

	</div>

</div><!--/.row-->