<?php
// Test image path update script
$conn = new mysqli('localhost', 'root', '', 'db_sp_checklist');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
mysqli_set_charset($conn, 'utf8mb4');

echo "🔧 Testing image path update...\n\n";

// Update P1 image path
$sql = "UPDATE `เมืองสมุทรปราการ` SET `image_path` = '../../image/products/water/คริสตัล350มล..jpg' WHERE `product_code` = 'P1'";
$result = $conn->query($sql);

if ($result) {
    echo "✅ Successfully updated P1 image path\n";
} else {
    echo "❌ Error updating P1: " . $conn->error . "\n";
}

// Check current data
$result = $conn->query("SELECT product_code, product_name, image_path FROM `เมืองสมุทรปราการ` ORDER BY product_code LIMIT 3");

echo "\n📋 Current data in เมืองสมุทรปราการ:\n";
echo "Code | Name | Image Path\n";
echo "------|------|------------\n";

while ($row = $result->fetch_assoc()) {
    echo $row['product_code'] . " | " . substr($row['product_name'], 0, 20) . "... | " . $row['image_path'] . "\n";
}

$conn->close();
echo "\n✅ Script completed\n";
?>