<?php
session_start();
// Простая аутентификация
if (!isset($_SESSION['admin'])) {
    header('Location: admin_login.php');
    exit;
}

$host = 'localhost';
$dbname = 'dozasquad_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $conn->prepare("SELECT * FROM contacts ORDER BY created_at DESC");
    $stmt->execute();
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель DozaSquad</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #001aff; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .count { background: #001aff; color: white; padding: 5px 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Админ-панель DozaSquad</h1>
    <p>Всего сообщений: <span class="count"><?php echo count($messages); ?></span></p>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Телефон</th>
                <th>Сообщение</th>
                <th>Дата</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($messages as $message): ?>
            <tr>
                <td><?php echo $message['id']; ?></td>
                <td><?php echo htmlspecialchars($message['name']); ?></td>
                <td><?php echo htmlspecialchars($message['email']); ?></td>
                <td><?php echo htmlspecialchars($message['phone']); ?></td>
                <td><?php echo htmlspecialchars($message['message']); ?></td>
                <td><?php echo $message['created_at']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>