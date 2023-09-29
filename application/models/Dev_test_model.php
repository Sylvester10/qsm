<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Dev_test_model
Role: Model
Description: Controls the DB processes of developer test functions
Controller: Dev_test
Author: Nwankwo Ikemefuna
Date Created: 23rd August, 2018
*/

class Dev_test_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->super_admin_details = $this->common_model->get_super_admin_details($this->session->super_admin_email);
    }
    
    
    

    function missing_integer(array $integers) {

        /*
        Problem: 
        Write a function:

        that, given an array A of N integers, returns the smallest positive integer (greater than 0) that does not occur in A.

        For example, given A = [1, 3, 6, 4, 1, 2], the function should return 5.

        Given A = [1, 2, 3], the function should return 4.

        Given A = [4, 5, 8, 3], the function should return 1. 

        Given A = [-1, -3], the function should return 1.
        */

        //OPTIMUM SOLUTION (HIGH PERFORMANCE)
        //Source: https://hackernoon.com/codility-demo-test-solution-3b01edb7b3c4

        //create a range btw 0 and the (max value of the array + 1) to hold the keys that will be matched against the integers array values
        $start_range = 0; //array key indexing start from 0
        $end_range = abs(max($integers)) + 1;
        $range = range($start_range, $end_range);

        //loop through the A array and compare each value to the value in the range array. If a match is made, set to null 
        foreach ($integers as $value) {
            $range[$value] = null;
        }

        //ensure zero is not returned by deleting range key 0
        unset($range[0]);
        
        //loop through the range array and return the first key with a non-null value
        $range_len = count($range);
        for ($int = 1; $int <= $range_len; $int++) {
            if ( ! is_null($range[$int]) ) {
                return $int;
            }
        }
    }


    function missing_integer2(array $A) {
        
        //SOLUTION USING GREEDY ALGORITHM (LOW PERFORMANCE)
     
        //round up each element of the A array less than 0 to zero
        $new_A = [];
        foreach ($A as $val) {
            if ($val < 0) {
                $new_val = 0;
            }  else {
                $new_val = $val;
            }
            $new_A[] = $new_val;
        }

        //if 1 is not in the new_A array, return 1
        if ( ! in_array(1, $new_A) ) {
            $result = 1;

        //if new_A has only a single element, return 1 if the value is not 1, return 2 otherwise
        } elseif (count($new_A) === 1) {
            
            if ($new_A[0] === 1) {
                $result = 1+1;
            } else {
                $result = 1;
            }
            
        } else {

        //find the min and max of new_A array
            $min = min($new_A);
            $max = max($new_A);

            //loop through the array of values btw min and max and create an array of numbers not in the new_A array
            $not_vals = [];
            for ($num = $min; $num < $max; $num++) {
                if ( ! in_array($num, $new_A) ) {
                $not_vals[] = $num;
                }
            } 
        
            //if there is no value not in the new_A exists btw min and max, add 1 to max
            if (count($not_vals) === 0) {
                $result = $max + 1;
            } else {
                //find the min of the not_vals array
                $result = min($not_vals);
            }
            
        }
        
        return $result;
    }


    function solution($A, $B) {
        // write your code in PHP7.0

        /*
        Problem
        A[0] = 0
        A[1] = 1
        A[2] = 2
        A[3] = 2
        A[4] = 3
        A[5] = 5

        Problem
        B[0] = 500 000
        B[1] = 500 000
        B[2] = 0
        B[3] = 0
        B[4] = 0
        B[5] = 20 000

        //C shld represent real numbers such that C[i] = A[i] + (B[i] / 1000000)
        C[0] = 0.5
        C[1] = 1.5
        C[2] = 2.0
        C[3] = 2.0
        C[4] = 3.0
        C[5] = 5.02

        A pair of indices (P, Q) is multiplicative if 
        C[P] * C[Q] >=  C[P] + C[Q]

        the above function will return the following
        (1, 4), because (1.5 * 3.0 = 4.5) >= (1.5 + 3.0 = 4.5)
        (1, 5), because (1.5 * 5.02 = 7.53) >= (1.5 + 5.02 = 6.52)
        etc

        function should return the number of multiplicative pairs if less than 1 mill, and 1 mill if more than 1 mill
        */

        //loop through array B and divide values by 1,000,000
        $new_B = [];
        foreach ($B as $value) {
            $new_val = $value / 1000000;
            $new_val = number_format($new_val, 2); //round off to 2 d.p
            $new_B[] = $new_val;
        }

        //loop through new_B array
        $C = [];
        foreach ($new_B as $key => $value) {
            //compare keys of A and B to get the C values
            if ($key = $A[$key]) {
            }
        }

    }




}