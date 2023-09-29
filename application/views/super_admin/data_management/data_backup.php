
<h3>Database Backup</h3>
<p>Last backup date: <?php echo x_date_full($last_backup_date); ?> (<?php echo time_ago($last_backup_date); ?>)</p>
<p>Last backup creator: <?php echo $last_backup_by; ?></p>
<a class="btn btn-success" href="<?php echo base_url('data_management/backup_database'); ?>" title="Create backup and download">Backup & Download</a>