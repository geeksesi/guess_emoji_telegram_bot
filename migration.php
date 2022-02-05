<?php
include __DIR__ . '/Model.php';

$model = new Model();

$model->make_levels_table();
$model->make_users_table();
echo "\n levels table is ok \n ";

echo "running levels seeds \n ";
$model->add_level('⌚️🐶🐶', 'watch dogs');
$model->add_level('🌟🐠', 'star fish');
$model->add_level('🌶🐶', 'hotdog');
$model->add_level('🔨🕔', 'break time');

echo 'successfully \n';
