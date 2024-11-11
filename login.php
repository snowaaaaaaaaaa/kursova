<?php
session_start();

$dbname = "users.db"; 

// тут підключаємось до нашої БД 
$conn = new PDO("sqlite:" . $dbname);

if (!$conn) {
    die("Ошибка подключения к базе данных.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // тут перевіряємо емеіл який ввів користувач
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // тут перевіряємо чи не існував такий користувач раніше
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // перевірка пароля
        if (password_verify($password, $row['password'])) {
            // найс, пароль успішний
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            echo "Вхід успішний!";
        } else {
            echo "Неправильний пароль.";
        }
    } else {
        echo "Користувача не знайдено.";
    }

    $stmt->closeCursor(); 
}

$conn = null; // всьо
?>
