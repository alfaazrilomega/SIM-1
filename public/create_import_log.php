<?php
$db = new mysqli('localhost', 'root', '', 'sim_orders');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Ensure the table exists
$sql = "CREATE TABLE IF NOT EXISTS import_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255),
    platform VARCHAR(50),
    total_rows INT,
    total_orders INT,
    inserted INT,
    updated INT,
    skipped INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";
if ($db->query($sql) === TRUE) {
    echo "Table import_log created successfully";
} else {
    echo "Error creating table: " . $db->error;
}
$db->close();
?>
