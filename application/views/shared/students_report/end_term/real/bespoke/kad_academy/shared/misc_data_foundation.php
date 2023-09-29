
<h4 class="extra_heading"><span>Identification of colours and shapes</span></h4>
<!--Colours-->
<table class="table report_table_extra">
    <thead>
        <tr>
            <th>Colours</th>
            <?php
            $colours = kad_academy_foundation_colours();
            foreach ($colours as $colour) { ?>
                <th class="text-center"><?php echo $colour; ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($terms as $key => $term_value) { ?>
            <tr>
                <td class="align_left"><?php echo $key; ?><sup><?php echo get_ordinal_value($key); ?> term</sup></td>
                <?php
                $d_result_details = $this->kad_students_end_term_report_model->get_result_details($session, $term_value, $class_id, $student_id);
                //ensure result exists for this term, else set as empty array
                $submitted_colours = ($d_result_details) ? explode(', ', $d_result_details->colours) : array();
                foreach ($colours as $colour) { 
                    $checked = (in_array($colour, $submitted_colours)) ? '<i class="fa fa-check"></i>' : NULL; ?>
                    <td><?php echo $checked; ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
</table>

<!--Shapes-->
<table class="table report_table_extra m-t-10">
    <thead>
        <tr>
            <th>Shapes</th>
            <?php
            foreach ($shapes as $shape) { ?>
                <th class="text-center"><?php echo $shape; ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($terms as $key => $term_value) { ?>
            <tr>
                <td class="align_left"><?php echo $key; ?><sup><?php echo get_ordinal_value($key); ?> term</sup></td>
                <?php
                $d_result_details = $this->kad_students_end_term_report_model->get_result_details($session, $term_value, $class_id, $student_id);
                //ensure result exists for this term, else set as empty array
                $submitted_shapes = ($d_result_details) ? explode(', ', $d_result_details->shapes) : array();
                foreach ($shapes as $shape) { 
                    $checked = (in_array($shape, $submitted_shapes)) ? '<i class="fa fa-check"></i>' : NULL; ?>
                    <td><?php echo $checked; ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
</table>



<h4 class="extra_heading"><span>letter <?php echo $letter_assessment_type; ?> assessment</span></h4>
<?php
foreach ($terms as $key => $term_value) { ?>
    <table class="table report_table_extra m-b-15">
        <thead>
            <tr>
                <th><?php echo $key; ?><sup><?php echo get_ordinal_value($key); ?> Term</sup></th>
                <?php
                $letters = range('A', 'Z');
                foreach ($letters as $letter) { ?>
                    <th class="text-center"><?php echo $letter; ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($letter_types as $db_column => $type) { ?>
                <tr>
                    <td class="align_left"><?php echo $type; ?></td>
                    <?php
                    $d_result_details = $this->kad_students_end_term_report_model->get_result_details($session, $term_value, $class_id, $student_id);
                    //ensure result exists for this term, else set as empty array
                    $submitted_letters = ($d_result_details) ? explode(', ', $d_result_details->$db_column) : array();
                    foreach ($letters as $letter) { 
                        $checked = (in_array($letter, $submitted_letters)) ? '<i class="fa fa-check"></i>' : NULL; ?>
                        <td><?php echo $checked; ?></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>



<h4 class="extra_heading"><span>Number recognition</span></h4>
<table class="table report_table_extra">
    <thead>
        <tr>
            <th>Term</th>
            <?php
            $numbers = range(1, 20);
            foreach ($numbers as $number) { ?>
                <th class="text-center"><?php echo $number; ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($terms as $key => $term_value) { ?>
            <tr>
                <td class="align_left"><?php echo $key; ?><sup><?php echo get_ordinal_value($key); ?> term</sup></td>
                <?php
                $d_result_details = $this->kad_students_end_term_report_model->get_result_details($session, $term_value, $class_id, $student_id);
                //ensure result exists for this term, else set as empty array
                $submitted_numbers = ($d_result_details) ? explode(', ', $d_result_details->numbers) : array();
                foreach ($numbers as $number) { 
                    $checked = (in_array($number, $submitted_numbers)) ? '<i class="fa fa-check"></i>' : NULL; ?>
                    <td><?php echo $checked; ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
</table>