<table class="table table-no-border report_table_header">
    <tr>
        <td>
            <div class="year-1-logo pull-left">
                <img src="<?php echo kad_school_logo; ?>" />
            </div>
        </td>

        <td class="align_left">
            <div class="text-bold m-t-50">
                <h3 class="report_section_title"><span>ELEMENTARY REPORT CARD</span></h3>
                <span class="report_data">HEAD OF SCHOOL</span>: <?php echo $head_of_school; ?>
            </div>
        </td>
    </tr>
</table>

<table class="table report_table_header report_header_info">
    <tbody>
        <tr>
            <td><strong>Student</strong></td>
            <td colspan="3"><?php echo $student_name; ?></td>
        </tr>                                   
        <tr>
            <td><strong>Date of Birth</strong></td>
            <td colspan="3"><?php echo $student_dob; ?></td>
        </tr>   
        <tr>
            <td><strong>School Year</strong></td>
            <td><?php echo $session; ?></td>
            <td><strong>Year</strong></td>
            <td><?php echo $class; ?></td>
        </tr>                                   
        <tr>
            <td><strong>Teacher</strong></td>
            <td colspan="3"><?php echo $class_teacher_name; ?></td>
        </tr>   
    </tbody>
</table>


<table class="table report_table_header year_3_6_legend_info">
    <thead>
        <tr class="text-bold text-center">
            <td colspan="13">
                <strong>Grade Criteria</strong>
            </td>
        </tr>
    </thead>
    <tbody>
        <tr class="text-center year_3_6_legend">
            <?php
            $legend = kad_academy_year_3_6_report_legend();
            foreach ($legend as $grade => $range) { ?>
                <td><?php echo $grade; ?><br><?php echo $range; ?></td>
            <?php } ?>
        </tr>
        <tr class="text-center">
            <td colspan="13"><strong>Student Performance Level</strong></td>
        </tr>
        <tr class="text-center">
            <td colspan="3"> 3 – Outstanding </td>
            <td colspan="3"> 2 – Satisfactory </td>
            <td colspan="4"> 1 – Unsatisfactory </td>
            <td colspan="4"> * - Not Assessed </td>
        </tr>
    </tbody>
</table>