
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "bihak");
$result = $conn->query("SELECT * FROM opportunities ORDER BY deadline ASC LIMIT 10");

$rows = array();
while($r = $result->fetch_assoc()) {
    $rows[] = $r;
}
echo json_encode($rows);
?>
