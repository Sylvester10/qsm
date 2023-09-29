
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>


<?php
$array = [
    [1, 3, 6, 4, 1, 2],
    [1, 2, 3],
    [4, 5, 8, 3],
    [-1, -3],
    [-1, -2, -5, 7, 11, 1],
    [0],
    [1],
    [13],
    [1, 15, 9, 500, 35, 955562, -108],
];

foreach ($array as $integers) {
    echo $this->dev_test_model->missing_integer($integers);
    echo '<br />';
} ?>


<hr />

<?php
$limit = 10; //define results limit
$offset = 20; //define offset
$i = 0; //start line counter
for ($i = 0; $i <= 5; $i++) {
    if ($i < $offset) {
        continue;
    }
    if ($i >= $offset + $limit) {
        break;
    }
    // do your stuff here
} ?>



<table class="table table-bordered">
    <tr><td>First Phase</td></tr>
    <?php
    for ($i = 0; $i <= 9; $i++) {
        if ($i == 5) {
            continue;
        } 
        // do your stuff here ?>
        <tr><td><?php echo $i; ?></td></tr>
    <?php } ?>
</table>

