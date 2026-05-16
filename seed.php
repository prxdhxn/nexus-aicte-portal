<?php

$admin = new App\Models\User();
$admin->name = 'Admin User';
$admin->email = 'admin@example.com';
$admin->password = bcrypt('password');
$admin->role = 'admin';
$admin->save();
echo "Admin created\n";

$sme = new App\Models\User();
$sme->name = 'SME User';
$sme->email = 'sme@example.com';
$sme->password = bcrypt('password');
$sme->role = 'sme';
$sme->save();
echo "SME created\n";

$institute = new App\Models\User();
$institute->name = 'Institute User';
$institute->email = 'institute@example.com';
$institute->password = bcrypt('password');
$institute->role = 'institute';
$institute->save();
echo "Institute created\n";

