

	<p><b>School name:</b> <?php echo $y->school_name; ?></p>
	<p><b>Location:</b> <?php echo $y->school_location; ?></p>
	<p><b>Country:</b> <?php echo $y->country; ?></p>
	<p><b>Official Email:</b> <?php echo $y->official_mail; ?></p>
	<p><b>Official Telephone Line:</b> <?php echo $y->telephone_line; ?></p>

	<?php if (school_website == software_website) { ?>

		<p><b>Website:</b> <?php echo $y->school_website; ?></p>

	<?php } else { ?>

		<p><b>Website:</b> <a href="<?php echo school_website; ?>" target="_blank" title="Visit school website"><?php echo $y->school_website; ?></a>

	<?php } ?>

	<p><b>School Motto:</b> <em><?php echo $y->school_motto; ?></em></p>