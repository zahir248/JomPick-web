<?php
include '../api/db_connection.php';
session_start();

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Fetch user data from the database, including hashed password and role_id
    $sql = "SELECT * FROM user WHERE LOWER(userName) = LOWER('$username')";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verify the entered password against the hashed password in the database
        if (password_verify($password, $row["password"])) {
            // Check if the user has a valid role (role_id 1 or 2 or 3)
            if ($row["role_id"] == 1 || $row["role_id"] == 2 || $row["role_id"] == 3) {
                // Admin role
                $_SESSION["id"] = $row["user_id"];
                $_SESSION["username"] = $row["userName"];
                $_SESSION["role_id"] = $row["role_id"];
                $_SESSION["jp_location_id"] = $row["jp_location_id"];
                header("Location: ../index.php");
                exit;
            } else {
                header("Location: ../login.php?error=Unauthorized access");
            }
        } else {
            header("Location: ../login.php?error=Login Invalid");
        }
    } else {
        header("Location: ../login.php?error=Login Invalid");
    }

    $conn->close();
}
?>