<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \App\Models\User::select('id','email','mobile','role','isActive','password')->get();
foreach ($users as $u) {
    echo $u->email . ' | ' . $u->mobile . ' | ' . $u->role . ' | active=' . var_export($u->isActive, true) . ' | hash=' . $u->password . PHP_EOL;
}
