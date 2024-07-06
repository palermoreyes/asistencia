<?php 

require '../../backend/bd/ctconex.php';  

// (B) DO SEARCH
$data = [];
$stmt = $connect->prepare("SELECT `dniem` FROM `empleado` WHERE `dniem` LIKE ?");
$stmt->execute(["%".$_POST["search"]."%"]);
while ($r = $stmt->fetch()) { $data[] = $r["dniem"]; }
echo count($data)==0 ? "null" : json_encode($data) ;
 ?>