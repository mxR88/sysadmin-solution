<?php
// ./app/index.php
echo "<h1>Hello from Apache + PHP 8.3!</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Test PostgreSQL connection
try {
    $pdo = new PDO(
        'pgsql:host=postgres;dbname=app_db',
        'app_user',
        'changeme_secure_password_123'
    );
    echo "<p>✅ PostgreSQL connected successfully!</p>";
} catch (PDOException $e) {
    echo "<p>❌ PostgreSQL connection failed: " . $e->getMessage() . "</p>";
}

// Check .htaccess support (create .htaccess file)
if (file_exists('.htaccess')) {
    echo "<p>✅ .htaccess is enabled</p>";
} else {
    echo "<p>ℹ️ Create .htaccess file to test support</p>";
}
?>
