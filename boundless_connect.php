<?php
$servername = "localhost";
$username = "Padmin";
$password = "12345";
$dbname = "boundless";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// 檢查連線
// -> :存取物件本身的屬性或方法
if ($conn->connect_error) {
	die("連線失敗: " . $conn->connect_error);
}
