<?php
$password = 'admin123'; // Replace with the actual password you want to hash

// Cost factor (e.g., 10) determines the computational effort
$options = [
    'cost' => 10,
];

// Generate the bcrypt hash
$hash = password_hash($password, PASSWORD_BCRYPT, $options);

echo "Generated Hash:".$hash;
?>