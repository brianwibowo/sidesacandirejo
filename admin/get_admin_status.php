<?php
session_start();
include "../koneksi/koneksi.php";
date_default_timezone_set('Asia/Jakarta');

$sql = "SELECT id_admin, last_active FROM tb_admin";
$query = mysqli_query($db, $sql);
$admins = [];

$now = time();
while($row = mysqli_fetch_assoc($query)){
    $online = false;
    if(!empty($row['last_active'])){
        $last = strtotime($row['last_active']);
        if(($now - $last) <= 120){
            $online = true;
        }
    }
    $admins[] = [
        'id_admin' => $row['id_admin'],
        'online' => $online,
        'last_active' => $row['last_active']
    ];
}

header('Content-Type: application/json');
echo json_encode($admins);