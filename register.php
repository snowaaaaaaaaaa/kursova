<?php
session_start();
header('Content-Type: application/json'); // тут для відповіді робимо json формат

$dbname = __DIR__ . "/users.db";

try {
    $conn = new PDO("sqlite:" . $dbname);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["message" => "Помилка підключення до бази даних: " . $e->getMessage()]);
    exit;
}

parse_str(file_get_contents("php://input"), $_POST);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    // тут перевіряємо емайл на дублі
    $checkEmailStmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
    $checkEmailStmt->bindParam(':email', $email);
    $checkEmailStmt->execute();

    if ($checkEmailStmt->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode(["message" => "Користувач з таким email вже існує. Будь ласка, використовуйте інший email."]);
    } else {
        // тут додаємо нового користувача 
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Реєстрація успішна!", "userName" => $name]);
        } else {
            echo json_encode(["message" => "Помилка при реєстрації: " . $stmt->errorInfo()[2]]);
        }
    }

    $checkEmailStmt->closeCursor(); 
}

$conn = null; // всьо
?>
