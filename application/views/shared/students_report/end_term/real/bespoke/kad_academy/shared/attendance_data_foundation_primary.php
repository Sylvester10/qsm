<table class="table table-no-border m-t-10">
    <tbody>
        <tr>

            <td>
                <table class="table report_table_extra t_left">
                    <thead>
                        <tr>
                            <th class="align_left">ATTENDANCE</th>
                            <?php
                            foreach ($terms as $key => $term_value) { ?> 
                                <th class="text-center"><?php echo $key; ?><sup><?php echo get_ordinal_value($key); ?></sup></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($attendance_params as $db_column => $type) { ?>
                            <tr>
                                <td class="align_left"><?php echo $type; ?></td>
                                <?php
                                foreach ($terms as $key => $term_value) { 
                                    $d_result_details = $this->kad_students_end_term_report_model->get_result_details($session, $term_value, $class_id, $student_id);
                                    //ensure result exists for this term, else set as empty array
                                    $submitted_att = ($d_result_details) ? $d_result_details->$db_column : NULL; ?>
                                    <td><?php echo $submitted_att; ?></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </td>

            <td>
                <table class="table report_table_extra t_right">
                    <thead>

                        <?php
                        if ($result_details) { 

                            //date entered
                            $de_day = ($last_promoted != NULL) ? date('d', strtotime($last_promoted)) : NULL;
                            $de_month = ($last_promoted != NULL) ? date('m', strtotime($last_promoted)) : NULL;
                            $de_year = ($last_promoted != NULL) ? date('Y', strtotime($last_promoted)) : NULL;
                            
                            //date collected: Date term ends
                            $date_collected = $term_info->term_closing_date;
                            $dc_day = date('d', strtotime($date_collected));
                            $dc_month = date('m', strtotime($date_collected));
                            $dc_year = date('Y', strtotime($date_collected)); ?>

                            <tr>
                                <th>Date entered</th>
                                <th class="text-center"><?php echo $de_day; ?></th>
                                <th class="text-center"><?php echo $de_month; ?></th>
                                <th class="text-center"><?php echo $de_year; ?></th>
                            </tr>
                            <tr>
                                <th>Promoted to</th>
                                <?php
                                //compare current clas and resumption class to know whether student is to be promoted or not 
                                if ($class_id != $resumption_class_id) { ?>
                                    <th colspan="3"><?php echo $resumption_class; ?></th>
                                <?php } else { ?>
                                    <th colspan="3"></th>
                                <?php } ?>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <th class="text-center"><?php echo $dc_day; ?></th>
                                <th class="text-center"><?php echo $dc_month; ?></th>
                                <th class="text-center"><?php echo $dc_year; ?></th>
                            </tr>

                        <?php } else { ?>

                            <tr>
                                <th>Date entered</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th>Promoted to</th>
                                <th colspan="3"></th>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>

                        <?php } ?>

                    </thead>
                </table>
            </td>

        </tr>
    </tbody>
</table>