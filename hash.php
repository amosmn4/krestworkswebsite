<?php
// Hash a password
$password = "Admin@Krest2026";
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

echo "Hashed: " . $hashedPassword . "\n";

// Verify a password against a hash
if (password_verify($password, $hashedPassword)) {
    echo "Password is correct!";
} else {
    echo "Password is incorrect!";
}
?>