<table class="table report_table_extra">
    <thead>
        <?php if ($skill_sub_title != '') { ?>
            <tr>
                <th colspan="4" class="text-center"><?php echo $skill_sub_title; ?></th>
            </tr>
        <?php } ?>
        <tr>
            <th class="align_left"><?php echo $skill_extra; ?></th>
            <?php
            foreach ($terms as $key => $term_value) { ?> 
                <th class="text-center"><?php echo $key; ?><sup><?php echo get_ordinal_value($key); ?></sup></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($skills as $s) { 
            
            $skill_id = $s->id;
            $skill = $s->skill; ?>
            <tr>
                <td class="align_left"><?php echo $skill; ?></td>
                <?php
                foreach ($terms as $key => $term_value) { 
                    $skill_score_query = $this->kad_students_end_term_report_model->check_skill_score_exists($session, $term_value, $class_id, $student_id, $skill_id); 
                    //ensure skill was produced for this term
                    if ($skill_score_query->num_rows() > 0) {
                        $skill_score_id = $skill_score_query->row()->id;
                        $skill_value = $this->kad_students_end_term_report_model->get_skill_score_details($skill_score_id)->skill;
                    } else {
                        $skill_value = NULL;
                    } ?>
                    <td><?php echo $skill_value; ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
</table>

