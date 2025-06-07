
<?php
function getUserByUsername($username) {
    global $conn;
    $sql = "SELECT * FROM user_account WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function insertUser($username, $password) {
    global $conn;
    $sql = "INSERT INTO user_account (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $password);
    return $stmt->execute();
}

function updatePassword($username, $newPassword) {
    global $conn;
    $sql = "UPDATE user_account SET password = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $newPassword, $username);
    return $stmt->execute();
}
?>
