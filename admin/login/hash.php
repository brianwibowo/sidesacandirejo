<?php
// Membuat hash bcrypt dari password
$password = password_hash('admin2', PASSWORD_BCRYPT);
echo $password;
?>
