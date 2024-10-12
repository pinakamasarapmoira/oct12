<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $fullname = trim($_POST["fullName"]);
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirmPassword"]);


    // Validate confirm password
    if ($password === $confirmPassword) {
        $host = "localhost";
        $database = "ecommerce";
        $dbUsername = "root";
        $dbPassword = "";


        $dsn = "mysql:host=$host;dbname=$database;";


        try {
            // Create a new PDO instance
            $conn = new PDO($dsn, $dbUsername, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
           
            $stmt = $conn->prepare('INSERT INTO users (fullname, username, password, created_at, updated_at) VALUES (:p_fullname, :p_username, :p_password, NOW(), NOW())');
            $stmt->bindParam(':p_fullname', $fullname);
            $stmt->bindParam(':p_username', $username); // Fixed binding for username
            $stmt->bindParam(':p_password', $password); // Hash the password
            $password = password_hash($password, PASSWORD_BCRYPT);
            $stmt->execute();


            header("Location: registration.php?success=Registration successful!");
            exit;
        } catch (PDOException $e) {
            // Redirect with error message
            header("Location: registration.php?error=Connection failed: " . urlencode($e->getMessage()));
            exit;
        }
    } else {
        header("Location: registration.php?error=Passwords do not match.");
        exit;
    }
}
?>
