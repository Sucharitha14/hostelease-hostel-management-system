<?php
require 'includes/config.inc.php';

// Check DB connection
if ($conn->connect_error) {
    die("❌ DB Connection failed: " . $conn->connect_error);
} else {
    echo "✅ DB connected to: " . $conn->host_info . "<br>";
    echo "✅ Database: hostelease_db <br><br>";
}

// Check managers table
$result = $conn->query("SELECT * FROM managers");
if (!$result) {
    die("❌ Query failed: " . $conn->error);
}

echo "Rows in managers table: " . $result->num_rows . "<br><br>";

while ($row = $result->fetch_assoc()) {
    echo "Username: " . $row['manager_uname'] . "<br>";
    echo "Hash stored: " . $row['pwd'] . "<br><br>";

    // Test password verify
    $test = password_verify('warden123', $row['pwd']);
    echo "password_verify result: " . ($test ? "✅ TRUE" : "❌ FALSE") . "<br>";
}
?>