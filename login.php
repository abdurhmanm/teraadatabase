<?php
include 'db_connection.php'; // تضمين كود الاتصال

// تفعيل عرض الأخطاء
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // استقبال بيانات تسجيل الدخول
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // التحقق من إدخال الحقول المطلوبة
    if (empty($email) || empty($password)) {
        echo "<script>alert('البريد الإلكتروني وكلمة المرور مطلوبة.'); window.history.back();</script>";
        exit();
    }

    // التحقق من بيانات المستخدم
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // التحقق من كلمة المرور
        if (password_verify($password, $user['password'])) {
            echo "<script>alert('أهلاً بك!'); window.location.href='index.html';</script>";
        } else {
            echo "<script>alert('كلمة المرور غير صحيحة!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('لا يوجد حساب بهذا البريد الإلكتروني.'); window.history.back();</script>";
    }

    // إغلاق الاتصال
    $stmt->close();
    $conn->close();
}
?>
