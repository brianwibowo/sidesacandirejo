<?php
$id_test = 23;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test ID</title>
</head>
<body>
    <form action="some_processor.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id_test); ?>">
        <input type="submit" value="Submit">
    </form>
    <p>Debug ID: <?php echo $id_test; ?></p>
</body>
</html>