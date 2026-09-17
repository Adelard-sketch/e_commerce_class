<?php
require_once 'db.php';

$allowed_statuses = ['pending', 'in_progress', 'done'];
$title = '';
$description = '';
$status = 'pending';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'pending';

    if ($title === '') {
        $error = 'Title is required.';
    } elseif (!in_array($status, $allowed_statuses, true)) {
        $error = 'Choose a valid status.';
    } else {
        $stmt = $conn->prepare("INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $description, $status);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        header("Location: index.php");
        exit;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Task</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="shell shell--narrow">
    <section class="panel">
        <p class="eyebrow">Tasks App</p>
        <h1>Add task</h1>
        <?php if ($error !== ''): ?><p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        <form method="post">
            <label for="title">Title</label>
            <input id="title" name="title" type="text" maxlength="255" value="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" rows="6"><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></textarea>

            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="in_progress" <?= $status === 'in_progress' ? 'selected' : '' ?>>In progress</option>
                <option value="done" <?= $status === 'done' ? 'selected' : '' ?>>Done</option>
            </select>

            <div class="form-actions">
                <button class="button" type="submit">Save task</button>
                <a href="index.php">Back</a>
            </div>
        </form>
    </section>
</main>
</body>
</html>
