
<?php
$conn = new mysqli('localhost', 'root', '', 'bihak');
$result = $conn->query("SELECT * FROM usagers ORDER BY created_at DESC");

echo '<div class="user-tiles">';
while ($row = $result->fetch_assoc()) {
    echo '<div class="tile">';
    echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
    echo '<p>' . substr(htmlspecialchars($row['description']), 0, 100) . '...</p>';
    echo '<a href="user_detail.php?id=' . $row['id'] . '" class="btn">Read More</a>';
    echo '</div>';
}
echo '</div>';
?>
<style>
.user-tiles { display: flex; flex-wrap: wrap; gap: 15px; }
.tile { flex: 1 1 30%; padding: 15px; border: 1px solid #ccc; background: #f9f9f9; }
.tile:first-child { flex: 1 1 100%; font-size: 1.2em; }
</style>
