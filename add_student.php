<?php

require_once 'src/Database.php';

$message = '';
$messageClass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name  = trim($_POST['first_name'] ?? '');
    $last_name   = trim($_POST['last_name'] ?? '');
    $programme   = trim($_POST['programme'] ?? null);

    if (empty($first_name) || empty($last_name)) {
        $message = "Error: Missing mandatory student name fields.";
        $messageClass = "error";
    } else {
        try {
          
            $db = Database::getInstance()->getConnection();

           
            $currentYear = date('Y');
            
     
            $query = "SELECT student_number FROM students WHERE student_number LIKE :yearPattern ORDER BY student_number DESC LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->execute([':yearPattern' => $currentYear . '%']);
            $lastStudent = $stmt->fetch();

            if ($lastStudent) {
              
                $lastSequence = (int)substr($lastStudent['student_number'], 4);
                $nextSequence = $lastSequence + 1;
            } else {
            
                $nextSequence = 1;
            }

            
            $student_num = $currentYear . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);
       

           
            $sql = "INSERT INTO students (student_number, first_name, last_name, programme, year_of_study) 
                    VALUES (:student_num, :first_name, :last_name, :programme, 1)";
            
            $insertStmt = $db->prepare($sql);
            $insertStmt->execute([
                ':student_num' => $student_num,
                ':first_name'  => $first_name,
                ':last_name'   => $last_name,
                ':programme'   => $programme
            ]);

           
               header("Location: dashboard.php?added=1");

            exit;

        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "Database Error: Unique constraint violation. Please retry.";
            } else {
                $message = "System Error: " . $e->getMessage();
            }
            $messageClass = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Student Record</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 40px; color: #333; }
        .form-card { max-width: 450px; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin: 0 auto; }
        h2 { margin-top: 0; color: #111; }
        input[type="text"] { width: 100%; padding: 10px; margin: 10px 0 20px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #1d4ed8; color: white; border: none; padding: 12px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; }
        button:hover { background: #1e40af; }
        .cancel-lnk { display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; font-weight: bold; }
        .error { background-color: #ffeeef; color: #dc2626; border: 1px solid #fca5a5; }
    </style>
</head>
<body>

    <div class="form-card">
        <h2>Add Student Record</h2>
        
        <?php if (!empty($message)): ?>
            <div class="alert <?php echo $messageClass; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <label>First Name</label>
            <input type="text" name="first_name" placeholder="Enter first name" required>

            <label>Last Name</label>
            <input type="text" name="last_name" placeholder="Enter last name" required>

            <label>Programme / Degree Department</label>
            <input type="text" name="programme" placeholder="e.g., Computer Science">

            <button type="submit">Save Student to System</button>
            <a href="students.php" class="cancel-lnk">Cancel and Go Back</a>
        </form>
    </div>

</body>
</html>
