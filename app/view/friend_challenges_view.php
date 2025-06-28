<!DOCTYPE html>
<html>
<head>
    <title>Friend Challenges</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: auto; padding: 20px; }
        h2, h3 { color: #007BFF; }
        form { margin-bottom: 20px; }
        label { display: block; margin-top: 10px; }
        select, input, textarea, button { width: 100%; padding: 8px; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        .msg { color: green; font-weight: bold; }
        .cheers-box { margin-top: 30px; background: #f0f8ff; padding: 15px; border-radius: 8px; }

        /* Popup styles */
        .popup-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
            z-index: 999;
        }
        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 600px;
            width: 90%;
            position: relative;
        }
        .popup-close {
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
            color: #c00;
        }
    </style>
</head>
<body>

<h2>Welcome, <?= htmlspecialchars($user_name) ?>!</h2>

<!-- ✅ 1. Challenge Creation Form -->
<h3>Create a Challenge</h3>
<form method="POST">
    <label>Title</label>
    <input type="text" name="title" required>

    <label>Goal (e.g. 5000)</label>
    <input type="number" name="goal" required>

    <label>Type</label>
    <select name="type" required>
        <option value="steps">Steps</option>
        <option value="reps">Reps</option>
        <option value="minutes">Minutes</option>
    </select>

    <button type="submit" name="create_challenge">Create Challenge</button>
</form>

<!-- ✅ 2. Challenge Selection -->
<h3>Available Challenges</h3>
<form method="POST">
    <label>Select Challenge</label>
    <select name="challenge_id" required>
        <option value="">-- Choose a Challenge --</option>
        <?php foreach ($challenges as $challenge): ?>
            <option value="<?= $challenge['id'] ?>" <?= ($selectedChallenge == $challenge['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($challenge['title']) ?> (<?= htmlspecialchars($challenge['goal']) ?> <?= htmlspecialchars($challenge['type']) ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="view_leaderboard">View Leaderboard</button>
</form>

<!-- ✅ 3. Popup Leaderboard -->
<?php if (!empty($leaderboard)): ?>
    <div class="popup-overlay" id="leaderboardPopup" style="display: flex;">
        <div class="popup-content">
            <span class="popup-close" onclick="closePopup()">×</span>
            <h3>Leaderboard</h3>
            <table>
                <tr>
                    <th>User</th>
                    <th>Progress</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($leaderboard as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['progress']) ?></td>
                        <td>
                            <?php if ($user['user_id'] != $current_user_id): ?>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="receiver_id" value="<?= $user['user_id'] ?>">
                                    <input type="hidden" name="challenge_id" value="<?= $selectedChallenge ?>">
                                    <input type="text" name="message" placeholder="Cheer message..." required>
                                    <button type="submit" name="send_cheer">Send Cheer</button>
                                </form>
                            <?php else: ?>
                                <em>👤 You</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <script>
        function closePopup() {
            document.getElementById("leaderboardPopup").style.display = "none";
        }
    </script>
<?php endif; ?>

<!-- ✅ 4. Cheers Received Section -->
<?php if (!empty($cheers)): ?>
    <div class="cheers-box">
        <h4>💌 Cheers You Received for This Challenge</h4>
        <ul>
            <?php foreach ($cheers as $c): ?>
                <li>
                    <strong><?= htmlspecialchars($c['sender_name']) ?></strong>: 
                    <?= htmlspecialchars($c['message']) ?> 
                    <em>(<?= htmlspecialchars($c['sent_at']) ?>)</em>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- ✅ 5. Flash Message -->
<?php if ($msg): ?>
    <p class="msg"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

</body>
</html>
