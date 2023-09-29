<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    function kad_academy_report_terms() {
        $terms = [
            1 => '1st',
            2 => '2nd',
            3 => '3rd',
        ];
        return $terms;
    }


    function kad_academy_year_3_6_report_legend() {
        $legend = [
            'A'     =>  '100 - 95',
            'A-'    =>  '94 - 90',
            'B+'    =>  '89 - 87',
            'B'     =>  '86 - 84',
            'B-'    =>  '83 - 80',
            'C+'    =>  '79 - 78',
            'C'     =>  '77 - 74',
            'C-'    =>  '73 - 70',
            'D+'    =>  '69 - 68',
            'D'     =>  '67 - 64',
            'D-'    =>  '63 - 60',
            'E'     =>  '59 - 0',
            'I'     =>  'Incomplete',
        ];
        return $legend;
    }


    function kad_academy_year_1_2_skill_legend() {
        $legend = [
            'O'     =>  'Exceeding Grade Level Expectations for this term',
            'S'     =>  'Meeting Grade Level Expectations for this term',
            'N'     =>  'Working Toward Grade Level Expectations for this term',
            'AC'    =>  'Below Grade Level Expectations for this term (Area of Concern)',
            'N/A'   =>  'Not Assessed this term',
        ];
        return $legend;
    }


    
    function kad_academy_foundation_primary_progress_legend($progress, $category) {
        $foundation_categories = ['pre_kg', 'kg', 'snr_kg'];
        if (in_array($category, $foundation_categories)) {
            //foundation classes
            $legend = [
                1 => '+', //Mastered
                2 => '<i class="fa fa-check"></i>', //Satisfactory
                3 => '-', //Not mastered
                4 => '*', //Not evaluated
            ];
        } else {
            //primary classes (year 2-6)
            $legend = [
                1 => 'O', 
                2 => 'S',
                3 => 'N', 
                4 => 'AC',
                5 => 'N/A',
            ];
        }
        return $legend[$progress];
    }


    function kad_academy_foundation_colours() {
        $colours = ['Red', 'Green', 'Brown', 'Blue', 'Orange', 'Black', 'Yellow', 'Purple'];
        return $colours;
    }


    function kad_academy_pre_kg_shapes() {
        $shapes = ['Circle', 'Rectangle', 'Square', 'Triangle'];
        return $shapes;
    }


    function kad_academy_kg_snr_kg_shapes() {
        $shapes = ['Circle', 'Rectangle', 'Square', 'Triangle', 'Rhombus', 'Ellipse'];
        return $shapes;
    }


    function kad_academy_pre_kg_letter_types() {
        $letter_types = [
            'letters_upper'     =>  'Identifies uppercase',
        ];
        return $letter_types;
    }


    function kad_academy_kg_snr_kg_letter_types() {
        $letter_types = [
            'letters_upper'     =>  'Identifies uppercase',
            'letters_lower'     =>  'Identifies lowercase',
            'letters_isolated'  =>  'Identifies isolated sound',
        ];
        return $letter_types;
    }


    function kad_academy_attendance_params() {
        $attendance = [
            'att_present'    =>     'Days present',
            'att_absent'     =>     'Days absent',
            'att_tardy'      =>     'Times tardy',
        ];
        return $attendance;
    }
    


    
