
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


<?php require("application/views/super_admin/publications/modals/update_announcement.php");  ?>


<?php 
//delete confirm
$delete_confirm = modal_delete_confirm($announcement->id, 'Announcement', 'announcement', 'publications_super_admin/delete_announcement');
echo $delete_confirm;

$published = $announcement->published;
if ($published == 'true') {
    $status = '<b class="text-success">Published</b>';
    $action = 'unpublish_announcement';
    $button_text = 'Unpublish';
    $icon = 'fa fa-eye-slash';
} else {
    $status = '<b class="text-danger">Unpublished</b>';
    $action = 'publish_announcement';
    $button_text = 'Publish';
    $icon = 'fa fa-eye';
} ?>

<div class="new-item">

    <button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#update_announcement"><i class="fa fa-pencil"></i> Update</button>

    <a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('publications_super_admin/'.$action); ?>"><i class="<?php echo $icon; ?>"></i> <?php echo $button_text; ?></a>

</div>

<p><?php echo $announcement->announcement; ?></p>
<p><small>Last updated: <?php echo time_ago($announcement->date); ?></small></p>
<p><small>Status: <?php echo $status; ?></small></p>

<?php if ($published == 'true') { ?>
    
    <p class="m-t-30">Announcement will be broadcasted to all school admins.</p>

    <a class="btn btn-success" href="<?php echo base_url('demo_accounts/super_user_login_admin/'.$demo_admin_id); ?>" target="_blank">See in Demo Account</a>

<?php } ?>

