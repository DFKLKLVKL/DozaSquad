<?php
// Настройки подключения к базе данных
$host = 'localhost';
$dbname = 'dozasquad_db';
$username = 'root';
$password = '';

// Включение отображения ошибок
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Устанавливаем заголовок для JSON
header('Content-Type: application/json');

// Проверка, что форма отправлена методом POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Подключение к базе данных
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Получение данных из формы
        $name = htmlspecialchars(trim($_POST['name']));
        $email = htmlspecialchars(trim($_POST['email']));
        $phone = htmlspecialchars(trim($_POST['phone']));
        $message = htmlspecialchars(trim($_POST['message']));
        
        // Валидация данных
        $errors = [];
        
        if (empty($name)) {
            $errors[] = "Имя обязательно для заполнения";
        }
        
        if (empty($email)) {
            $errors[] = "Email обязателен для заполнения";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Некорректный формат email";
        }
        
        // Если есть ошибки, выводим их
        if (!empty($errors)) {
            echo json_encode([
                'success' => false,
                'message' => 'Ошибки в форме:',
                'errors' => $errors
            ]);
            exit;
        }
        
        // Подготовка SQL запроса
        $sql = "INSERT INTO contacts (name, email, phone, message) VALUES (:name, :email, :phone, :message)";
        $stmt = $conn->prepare($sql);
        
        // Привязка параметров
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':message', $message);
        
        // Выполнение запроса
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Спасибо! Ваше сообщение отправлено.'
            ]);
            
            // Отправка email уведомления (опционально)
            $to = "DozaSquad@gmail.com";
            $subject = "Новое сообщение с сайта DozaSquad";
            $headers = "From: " . $email . "\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            
            $email_body = "
            <h2>Новое сообщение с сайта DozaSquad</h2>
            <p><strong>Имя:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Телефон:</strong> $phone</p>
            <p><strong>Сообщение:</strong></p>
            <p>$message</p>
            <hr>
            <p><em>Отправлено " . date('d.m.Y H:i:s') . "</em></p>
            ";
            
            // Раскомментируйте для отправки email
            // mail($to, $subject, $email_body, $headers);
            
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Ошибка при сохранении данных.'
            ]);
        }
        
    } catch(PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Ошибка подключения к базе данных. Проверьте настройки подключения.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Неверный метод запроса.'
    ]);
}
?>