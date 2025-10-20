<?php
// Update all image paths script
$conn = new mysqli('localhost', 'root', '', 'db_sp_checklist');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
mysqli_set_charset($conn, 'utf8mb4');

echo "🔧 Updating all image paths...\n\n";

$tables = ['เมืองสมุทรปราการ', 'พระประแดง', 'พระสมุทรเจดีย์', 'บางพลี', 'บางบ่อ', 'บางเสาธง'];

$updates = [
    'P1' => '../../image/products/water/คริสตัล350มล..jpg',
    'P2' => '../../image/products/water/คริสตัล600มล..jpg',
    'P3' => '../../image/products/water/คริสตัล1,000มล..jpg',
    'P4' => '../../image/products/water/คริสตัล1,500มล..jpg',
    'P5' => '../../image/products/water/เนสท์เล่ 330มล.jpg',
    'P6' => '../../image/products/water/เนสท์เล่600มล..jpg',
    'P7' => '../../image/products/water/เนสท์เล่1,500มล.jpg',
    'P8' => '../../image/products/water/เนสท์เล่6,000มล.jpg'
];

foreach ($tables as $table) {
    echo "📊 Updating table: $table\n";
    
    foreach ($updates as $code => $path) {
        $sql = "UPDATE `$table` SET `image_path` = '$path' WHERE `product_code` = '$code'";
        $result = $conn->query($sql);
        
        if ($result) {
            echo "  ✅ Updated $code\n";
        } else {
            echo "  ❌ Error updating $code: " . $conn->error . "\n";
        }
    }
    echo "\n";
}

// Verify one table
echo "🔍 Verification - เมืองสมุทรปราการ table:\n";
$result = $conn->query("SELECT product_code, image_path FROM `เมืองสมุทรปราการ` ORDER BY product_code");

while ($row = $result->fetch_assoc()) {
    echo "  " . $row['product_code'] . " → " . $row['image_path'] . "\n";
}

$conn->close();
echo "\n✅ All updates completed!\n";
?>