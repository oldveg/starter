<?php

session_start();

$caminho = realpath(dirname(__FILE__));

include $caminho.'/../../../application/config/database.php';

$kfm_db_type                =   $db[$active_group]['kfm_db_type'];
$kfm_db_prefix              =   $db[$active_group]['kfm_prefix'];
$kfm_db_host                =   $db[$active_group]['hostname'];
$kfm_db_name                =   $db[$active_group]['database'];
$kfm_db_username            =   $db[$active_group]['username'];
$kfm_db_password            =   $db[$active_group]['password'];
$kfm_db_port                =   $db[$active_group]['kfm_port'];
$use_kfm_security           =   false;
$kfm_userfiles_address      =   $caminho.'/../../../uploads';
$kfm_userfiles_output       =   $_SESSION['base_url'] . 'uploads';
$kfm_workdirectory          =   '.files-sqlite-pdo';
#$kfm_imagemagick_path      =   '/usr/bin/convert';
$kfm_imagemagick_path       =   '/usr/bin/cnvert';
$kfm_dont_send_metrics      =   0;
$kfm_server_hours_offset    =   1;