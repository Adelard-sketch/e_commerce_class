<?php
require_once 'db.php';

$result = $conn->query("SELECT * FROM tasks ORDER BY created_at DESC");

function status_label(string $status): string
{
    return match ($status) {
        'in_progress' => 'In progress',
        'done' => 'Done',
        default => 'Pending',
    };
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tasks</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="shell">
    <header class="page-header">
        <div>
            <p class="eyebrow">Tasks App</p>
            <h1>MY TASKS</h1>
        </div>
        <a class="button" href="create.php">+ Add task</a>
    </header>

    <?php if ($result->num_rows === 0): ?>
        <section class="panel empty-state">
            <h2>No tasks yet</h2>
            <p>Create your first task to get started.</p>
            <a href="create.php">Add a task</a>
        </section>
    <?php else: ?>
        <section class="task-list" aria-label="Task list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <article class="task-card">
                    <div class="task-card__body">
                        <div class="task-card__topline">
                            <h2><?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?></h2>
                            <span class="status status--<?= htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') ?>"><?= status_label($row['status']) ?></span>
                        </div>
                        <?php if ($row['description'] !== ''): ?>
                            <p><?= nl2br(htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8')) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="task-card__actions">
                        <a href="edit.php?id=<?= (int) $row['id'] ?>">Edit</a>
                        <a class="danger" href="delete.php?id=<?= (int) $row['id'] ?>" onclick="return confirm('Delete this task?');">Delete</a>
                    </div>
                </article>
            <?php endwhile; ?>
        </section>
    <?php endif; ?>
</main>
</body>
</html>
<?php $conn->close(); ?>
