<?php
include "../../koneksi/koneksi.php";

$id = $_POST['id'];
$password = sha1($_POST['password']);

mysqli_query($db,"
UPDATE tb_admin
SET password='$password'
WHERE id_admin='$id'
");

header("Location: ../manajemen_admin.php");
?>