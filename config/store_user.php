
<?php
$conn = new mysqli('localhost', 'root', '', 'bihak');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$age = $_POST['age'];
$description = $_POST['description'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$video_link = $_POST['video_link'];
$category = implode(', ', $_POST['category']);
$request = $_POST['request'];
$contacts = $_POST['contacts'];

$stmt = $conn->prepare("INSERT INTO usagers (name, age, description, username, password, video_link, category, request, contacts) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sisssssss", $name, $age, $description, $username, $password, $video_link, $category, $request, $contacts);

if ($stmt->execute()) {
    echo "User registered successfully.";
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
