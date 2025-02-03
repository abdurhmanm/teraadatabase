<?php
include 'db_connection.php'; // تضمين كود الاتصال

// تفعيل عرض الأخطاء
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // استقبال بيانات النموذج مع التحقق
    $full_name = isset($_POST['full_name']) ? $_POST['full_name'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';

    // التحقق من إدخال الحقول المطلوبة
    if (empty($full_name) || empty($username) || empty($email) || empty($password) || empty($phone_number)) {
        echo "<script>alert('جميع الحقول مطلوبة.'); window.history.back();</script>";
        exit();
    }

    // التحقق من تكرار اسم المستخدم أو البريد الإلكتروني
    $check_sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('اسم المستخدم أو البريد الإلكتروني موجود مسبقًا.'); window.history.back();</script>";
        exit();
    }

    // تشفير كلمة المرور
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // إدخال البيانات في قاعدة البيانات
    $insert_sql = "INSERT INTO users (full_name, username, email, password, phone_number) 
                   VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("sssss", $full_name, $username, $email, $hashed_password, $phone_number);

    if ($stmt->execute()) {
        echo "<script>alert('تم التسجيل بنجاح!'); window.location.href='index.html';</script>";
    } else {
        echo "خطأ أثناء التسجيل: " . $conn->error;
    }

    // إغلاق الاتصال
    $stmt->close();
    $conn->close();
}
?>
