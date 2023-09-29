<table class="table table-no-border report_table_header">
    <tr>
        <td>
            <div class="year-1-logo pull-left">
                <img src="<?php echo kad_school_logo; ?>" />
            </div>
        </td>

        <td class="align_left">
            <div class="text-bold m-t-50">
                <h3 class="report_section_title"><span><?php echo $class; ?> REPORT CARD</span></h3>
                <span class="report_data">HEAD OF SCHOOL</span>: <?php echo $head_of_school; ?>
            </div>
        </td>
    </tr>
</table>


<table class="table table-no-border">
    <tbody>
        <tr>

            <td>
                <table class="table report_table_header report_header_info t_left">
                    <tbody>
                        <tr>
                            <td><strong>Student</strong></td>
                            <td><?php echo $student_name; ?></td>
                        </tr>                                   
                        <tr>
                            <td><strong>Date of Birth</strong></td>
                            <td><?php echo $student_dob; ?></td>
                        </tr>   
                        <tr>
                            <td><strong>School Year</strong></td>
                            <td><?php echo $session; ?></td>
                        </tr>                                   
                        <tr>
                            <td><strong>Teacher</strong></td>
                            <td><?php echo $class_teacher_name; ?></td>
                        </tr>   
                    </tbody>
                </table>
            </td>

            <td>
                <table class="table report_table_header report_legend_info t_right m-t-10">
                    <thead>
                        <tr><th class="text-center">Performance Scale</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $skills = kad_academy_year_1_2_skill_legend();
                        foreach ($skills as $key => $value) { ?>
                            <tr><td><b><?php echo $key; ?></b> – <?php echo $value; ?></td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </td>

        </tr>
    </tbody>
</table>