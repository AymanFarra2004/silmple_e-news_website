<?php
// register.php

require_once 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);

    if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
        echo "<p>All fields are required.</p>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p>Invalid email format.</p>";
        exit;
    }

    if ($password !== $confirm) {
        echo "<p>Passwords do not match.</p>";
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<p>Email already registered.</p>";
        exit;
    }

    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed);

    if ($stmt->execute()) {
        echo "<p>Registration successful. You may now <a href='login.php'>login</a>.</p>";
    } else {
        echo "<p>Error: Could not register.</p>";
    }

    $stmt->close();
    $conn->close();
} else {
?>
<form method="POST" action="register.php">
  <h2>Register</h2>
  <input type="text" name="username" placeholder="Username" required><br>
  <input type="email" name="email" placeholder="Email" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <input type="password" name="confirm" placeholder="Confirm Password" required><br>
  <button type="submit">Register</button>
</form>
<?php
}
?>