
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>


	<div class="new-item">
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('parents'); ?>"><i class="fa fa-users"></i> All Parents</a>
	</div>

	<?php echo form_open('parents/edit_parent_action/'.$y->id); ?>


		<div class="">
			<h4><b>Child(ren) / Ward(s): <?php echo count($children); ?> </b></h4>

			<?php
			//List parents children
			$p_children = "";
			foreach ($children as $c) {
				$child_name = $this->common_model->get_student_fullname($c->id);
				$p_children .= '<div><i class="fa fa-child"></i> <a href="' . base_url('students_admin/edit_student/'.$c->id) . '" target="_blank" title="View/edit ' . $child_name . '">' . $child_name . '</a></div>';
			} 
			echo $p_children; ?>
		</div>

		
		<p class="m-t-20">All fields marked * are required.</p>

	
		<div class="row">

			<div class="col-md-6 col-sm-12 col-xs-12">

				
				<div class="">
					<h4><b>First Parents/Guardian Details</b></h4>
					<hr>
				</div>

				<div class="form-group">
					<label class="form-control-label">Name of Parent/Guardian*</label>
					<br/>
					<input type="text" name="parent_name" class="form-control" value="<?php echo set_value('parent_name', $y->name); ?>" required />
					<div class="form-error"><?php echo form_error('parent_name'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label m-r-20">Sex</label>
					<label><input type="radio" name="parent_sex" value="Male" <?php echo set_radio( 'parent_sex', 'Male', radio_value($y->sex, 'Male') ); ?> > Male</label>
					<label class="m-l-10"><input type="radio" name="parent_sex" value="Female" <?php echo set_radio( 'parent_sex', 'Female', radio_value($y->sex, 'Female') ); ?> > Female</label>
					<div class="form-error"><?php echo form_error('parent_sex'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Relationship to Child</label>
					<select class="form-control" name="parent_relationship" id="parent_relationship">
						<option selected value="<?php echo $y->relationship; ?>"><?php echo $y->relationship; ?></option>
						<option value="Father" <?php echo set_select('parent_relationship', 'Father'); ?> >Father</option>
						<option value="Mother" <?php echo set_select('parent_relationship', 'Mother'); ?> >Mother</option>
						<option value="Grand Parent" <?php echo set_select('parent_relationship', 'Grand Parent'); ?> >Grand Parent</option>
						<option value="Uncle" <?php echo set_select('parent_relationship', 'Uncle'); ?> >Uncle</option>
						<option value="Aunty" <?php echo set_select('parent_relationship', 'Aunty'); ?> >Aunty</option>
						<option value="Other" id="parent_relationship_other_option" <?php echo set_select('parent_relationship', 'Other'); ?> >Other</option>
					</select>
					<div class="form-error"><?php echo form_error('parent_relationship'); ?></div>
				</div>

				<div class="form-group" style="display: none;" id="parent_relationship_other_area">
					<label class="form-control-label">Specify relationship to child</label>
					<br/>
					<input type="text" name="parents_relationship_other" class="form-control" value="<?php echo set_value('parents_relationship_other'); ?>" />
					<div class="form-error"><?php echo form_error('parents_relationship_other'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Mobile</label>
					<br/>
					<input type="text" name="parent_phone" value="<?php echo set_value('parent_phone', $y->phone); ?>" class="form-control numbers-only" />
					<div class="form-error"><?php echo form_error('parent_phone'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Email <small>(Will be used by parents/guardians to access the Parent portal. Students of the same parent/guardian should have the same email address)</small></label>
					<br/>
					<input type="email" name="parent_email" class="form-control" value="<?php echo set_value('parent_email', $y->email); ?>" />
					<div class="form-error"><?php echo form_error('parent_email'); ?></div>
				</div>

			</div>



			<div class="col-md-6 col-sm-12 col-xs-12">
				
				<div class="">
					<h4><b>Second Parent/Guardian Details</b></h4>
					<hr>
				</div>

				<div class="form-group">
					<label class="form-control-label">Name of Parent/Guardian</label>
					<br/>
					<input type="text" name="sec_parent_name" class="form-control" value="<?php echo set_value('sec_parent_name', $y->sec_parent_name); ?>" />
					<div class="form-error"><?php echo form_error('sec_parent_name'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label m-r-20">Sex</label>
					<label><input type="radio" name="sec_parent_sex" value="Male" <?php echo set_radio( 'sec_parent_sex', 'Male', radio_value($y->sec_parent_sex, 'Male') ); ?> > Male</label>
					<label class="m-l-10"><input type="radio" name="sec_parent_sex" value="Female" <?php echo set_radio( 'sec_parent_sex', 'Female', radio_value($y->sec_parent_sex, 'Female') ); ?> > Female</label>
					<div class="form-error"><?php echo form_error('sec_parent_sex'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Relationship to Child</label>
					<select class="form-control" name="sec_parent_relationship" id="sec_parent_relationship">
						<option selected value="<?php echo $y->sec_parent_relationship; ?>"><?php echo $y->sec_parent_relationship; ?></option>
						<option value="Father" <?php echo set_select('sec_parent_relationship', 'Father'); ?> >Father</option>
						<option value="Mother" <?php echo set_select('sec_parent_relationship', 'Mother'); ?> >Mother</option>
						<option value="Grand Parent" <?php echo set_select('sec_parent_relationship', 'Grand Parent'); ?> >Grand Parent</option>
						<option value="Uncle" <?php echo set_select('sec_parent_relationship', 'Uncle'); ?> >Uncle</option>
						<option value="Aunty" <?php echo set_select('sec_parent_relationship', 'Aunty'); ?> >Aunty</option>
						<option value="Other" id="sec_parent_relationship_other_option" <?php echo set_select('sec_parent_relationship', 'Other'); ?> >Other</option>
					</select>
					<div class="form-error"><?php echo form_error('sec_parent_relationship'); ?></div>
				</div>

				<div class="form-group" style="display: none;" id="sec_parent_relationship_other_area">
					<label class="form-control-label">Specify relationship to child</label>
					<br/>
					<input type="text" name="sec_parents_relationship_other" class="form-control" value="<?php echo set_value('sec_parents_relationship_other'); ?>" />
					<div class="form-error"><?php echo form_error('sec_parents_relationship_other'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Mobile</label>
					<br/>
					<input type="text" name="sec_parent_phone" class="form-control numbers-only" value="<?php echo set_value('sec_parent_phone', $y->sec_parent_phone); ?>" />
					<div class="form-error"><?php echo form_error('sec_parent_phone'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Email</label>
					<br/>
					<input type="email" name="sec_parent_email" class="form-control" value="<?php echo set_value('sec_parent_email', $y->sec_parent_email); ?>" />
					<div class="form-error"><?php echo form_error('sec_parent_email'); ?></div>
				</div>
				
				
				<div class="m-t-10">
					<button class="btn btn-primary btn-lg">Update</button>
				</div>
			
			</div><!--/.col-->

			
		</div><!--/.row-->

		
	<?php echo form_close(); ?>

		