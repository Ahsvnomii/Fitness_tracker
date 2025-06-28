<<!DOCTYPE html>
<html>
<head>
    <title>Water Intake Tracker</title>
    <style>
        body { font-family: sans-serif; padding: 20px; max-width: 600px; margin: auto; }
        h2, h3 { color: #007BFF; }
        .progress { background: #eee; border-radius: 10px; overflow: hidden; margin: 10px 0; }
        .progress-bar { background: #00c853; height: 20px; color: white; text-align: center; }
        button, input { padding: 10px; margin-top: 10px; width: 100%; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border: 1px solid #ccc; text-align: center; }
        .msg { color: green; font-weight: bold; }
    </style>
</head>
<body>

<h2>Hydration Tracker</h2>

<?php
    $percentage = ($waterIntake / $dailyGoal) * 100;
    if ($percentage > 100) $percentage = 100;
    $displayIntake = min($waterIntake, $dailyGoal);
?>

<p>
    Today: <strong><?= $displayIntake ?> ml</strong> / <?= $dailyGoal ?> ml
    <br>
    <?php if ($waterIntake >= $dailyGoal): ?>
        <span style="color: green; font-weight: bold;">🎉 Goal Achieved!</span>
    <?php else: ?>
        <span style="color: orange;">⏳ Keep Going!</span>
    <?php endif; ?>
</p>

<div class="progress">
    <div class="progress-bar" style="width: <?= $percentage ?>%">
        <?= round($percentage) ?>%
    </div>
</div>

<?php if ($waterIntake < $dailyGoal): ?>
    <form method="POST">
        <button type="submit" name="log_cup">+ Log 1 Cup (250ml)</button>
    </form>
<?php else: ?>
    <p style="color: green;"><strong>✅ You've completed your hydration goal for today!</strong></p>
<?php endif; ?>

<h3>Set Your Daily Goal (ml)</h3>
<form method="POST">
    <input type="number" name="daily_goal" value="<?= $dailyGoal ?>" min="500" step="100" required>
    <button type="submit" name="update_goal">Update Goal</button>
</form>

<?php if (!empty($msg)): ?>
    <p class="msg"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<h3>Set Reminder Interval (in minutes)</h3>
<input type="number" id="reminderInterval" value="30" min="5" step="5" required>
<button onclick="setReminder()">Set Reminder</button>
<p id="reminderStatus" style="color:blue;"></p>

<h3>History</h3>
<table>
    <tr>
        <th>Amount (ml)</th>
        <th>Time</th>
    </tr>
    <?php foreach ($logs as $log): ?>
        <tr>
            <td><?= htmlspecialchars($log['amount']) ?></td>
            <td><?= htmlspecialchars($log['log_time']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<script>
let reminderTimer = null;

function setReminder() {
    const mins = parseInt(document.getElementById('reminderInterval').value);
    if (isNaN(mins) || mins < 5) {
        alert("Please enter a valid interval (minimum 5 minutes)");
        return;
    }

    if (reminderTimer) clearInterval(reminderTimer);

    reminderTimer = setInterval(() => {
        const intake = <?= $waterIntake ?>;
        const goal = <?= $dailyGoal ?>;

        const now = Date.now();
        const lastReminder = localStorage.getItem("lastReminderTime");
        const minGap = 1000 * 60 * 5; // 5 minutes gap between reminders

        if (document.hasFocus() && intake < goal && (!lastReminder || now - lastReminder > minGap)) {
            alert("💧 You're behind your hydration goal! Time to drink water.");
            localStorage.setItem("lastReminderTime", now);
        }

        if (intake >= goal) {
            clearInterval(reminderTimer);
            document.getElementById("reminderStatus").innerText = "✅ Goal achieved. No more reminders.";
        }
    }, mins * 60 * 1000);

    document.getElementById("reminderStatus").innerText = `🔔 Reminder set every ${mins} minutes.`;
}
</script>

</body>
</html>
