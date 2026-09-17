<?php
require_once 'db.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die('Invalid task ID.');
}

$allowed_statuses = ['pending', 'in_progress', 'done'];
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$task = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$task) {
    die('Task not found.');
}

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
        $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $description, $status, $id);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        header("Location: index.php");
        exit;
    }

    $task['title'] = $title;
    $task['description'] = $description;
    $task['status'] = $status;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Task</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="shell shell--narrow">
    <section class="panel">
        <p class="eyebrow">Tasks App</p>
        <h1>Edit task</h1>
        <?php if ($error !== ''): ?><p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        <form method="post">
            <label for="title">Title</label>
            <input id="title" name="title" type="text" maxlength="255" value="<?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?>" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" rows="6"><?= htmlspecialchars($task['description'], ENT_QUOTES, 'UTF-8') ?></textarea>

            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="pending" <?= $task['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="in_progress" <?= $task['status'] === 'in_progress' ? 'selected' : '' ?>>In progress</option>
                <option value="done" <?= $task['status'] === 'done' ? 'selected' : '' ?>>Done</option>
            </select>

            <div class="form-actions">
                <button class="button" type="submit">Update task</button>
                <a href="index.php">Back</a>
            </div>
        </form>
    </section>
</main>
</body>
</html>
