<!DOCTYPE html>
<html>
<head>
    <title>Workout Logger</title>
    <style>
        body { font-family: Arial; padding: 20px; max-width: 600px; margin: auto; }
        h2 { color: #333; }
        input, textarea, button { width: 100%; margin: 5px 0; padding: 10px; }
        .timer { font-size: 24px; margin-bottom: 10px; }
        .msg { color: green; font-weight: bold; }
    </style>
</head>
<body>

<h2>Workout Timer + Logger</h2>

<div class="timer" id="timer">00:00:00</div>
<button onclick="startTimer()">Start</button>
<button onclick="stopTimer()">Stop</button>
<button onclick="resetTimer()">Reset</button>

<form method="POST">
    <input type="hidden" name="duration" id="durationInput">
    <input type="text" name="exercise" placeholder="Exercise Name" required>
    <input type="number" name="sets" placeholder="Sets" required>
    <input type="number" name="reps" placeholder="Reps" required>
    <input type="number" step="any" name="weight" placeholder="Weight (kg)">
    <textarea name="note" placeholder="Any notes..."></textarea>
    <button type="submit" name="save">Save Session</button>
</form>

<?php if (!empty($msg)): ?>
    <p class="msg"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<h3>Session History</h3>
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>Exercise</th>
        <th>Sets</th>
        <th>Reps</th>
        <th>Weight</th>
        <th>Duration</th>
        <th>Note</th>
        <th>Date</th>
    </tr>
    <?php foreach ($sessions as $session): ?>
        <tr>
            <td><?= htmlspecialchars($session['exercise']) ?></td>
            <td><?= htmlspecialchars($session['sets']) ?></td>
            <td><?= htmlspecialchars($session['reps']) ?></td>
            <td><?= htmlspecialchars($session['weight']) ?> kg</td>
            <td><?= htmlspecialchars($session['duration']) ?></td>
            <td><?= htmlspecialchars($session['note']) ?></td>
            <td><?= htmlspecialchars($session['created_at']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>


<script>
let startTime, interval;

function updateTimer() {
    const now = new Date().getTime();
    const elapsed = new Date(now - startTime);
    const h = String(elapsed.getUTCHours()).padStart(2, '0');
    const m = String(elapsed.getUTCMinutes()).padStart(2, '0');
    const s = String(elapsed.getUTCSeconds()).padStart(2, '0');
    document.getElementById("timer").innerText = `${h}:${m}:${s}`;
    document.getElementById("durationInput").value = `${h}:${m}:${s}`;
}

function startTimer() {
    startTime = new Date().getTime();
    interval = setInterval(updateTimer, 1000);
}

function stopTimer() {
    clearInterval(interval);
}

function resetTimer() {
    clearInterval(interval);
    document.getElementById("timer").innerText = "00:00:00";
    document.getElementById("durationInput").value = "00:00:00";
}
</script>

</body>
</html>
