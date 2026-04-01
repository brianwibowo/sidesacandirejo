<?php
session_start();
unset($_SESSION['r3su']);
unset($_SESSION['username']);
unset($_SESSION['gambar']);
unset($_SESSION['nama']);
unset($_SESSION['id']);
session_destroy();
header("Location: ../");
exit();
