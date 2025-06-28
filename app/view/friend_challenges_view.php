
<!DOCTYPE html>
<html>
<head>
    <title>Friend Challenges</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: auto; padding: 20px; }
        h2, h3 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #3498db; color: white; }
        button { padding: 6px 12px; margin: 5px 0; cursor: pointer; }
        form { margin: 0; }
        .msg { color: green; font-weight: bold; margin-bottom: 10px; }
        /* Popup styles */
        #leaderboardPopup {
            display: none; /* hidden by default */
            position: fixed;
            top: 50px;
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            border: 2px solid #3498db;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            padding: 20px;
            max-width: 650px;
            max-height: 70vh;
            overflow-y: auto;
            z-index: 1000;
        }
        #leaderboardPopup h3 {
            margin-top: 0;
        }
        #leaderboardPopup .closeBtn {
            float: right;
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
            color: #e74c3c;
        }
        .cheerInput {
            width: 70%;
            padding: 5px;
        }
        .cheerBtn {
            padding: 5px 10px;
            background-color: #27ae60;
            border: none;
            color: white;
            cursor: pointer;
        }
        .youLabel {
            font-style: italic;
            color: #7f8c8d;
        }
        .received-cheers {
            margin-top: 40px;
            border-top: 2px solid #3498db;
            padding-top: 15px;
        }
        .received-cheers ul {
            list-style-type: none;
            padding-left: 0;
        }
        .received-cheers li {
            margin-bottom: 8px;
            background: #ecf0f1;
            padding: 10px;
            border-radius: 5px;
        }
        .received-cheers strong {
            color: #2980b9;
        }
        /* Create challenge form */
        #createChallengeForm {
            border: 1px solid #3498db;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
        }
        #createChallengeForm input, #createChallengeForm select {
            padding: 7px;
            margin-right: 10px;
            margin-bottom: 10px;
            width: 180px;
        }
        #createChallengeForm button {
            width: auto;
            padding: 8px 15px;
            background-color: #2980b9;
            color: white;
            border: none;
            cursor: pointer;
        }
        /* Progress button */
        .progressBtn {
            background-color: #e67e22;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            margin-left: 10px;
            border-radius: 3px;
        }
    </style>
</head>
<body>

<h2>Friend Challenges</h2>

<?php if (!empty($msg)): ?>
    <p class="msg"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<!-- Create Challenge Form -->
<div id="createChallengeForm">
    <h3>Create New Challenge</h3>
    <form method="POST">
        <input type="text" name="title" placeholder="Challenge Title" required>
        <input type="number" name="goal" placeholder="Goal (e.g. 10000 steps)" min="1" required>
        <select name="type" required>
            <option value="">Select Type</option>
            <option value="steps">Steps</option>
            <option value="workout">Workout</option>
            <option value="other">Other</option>
        </select>
        <button type="submit" name="create_challenge">Create</button>
    </form>
</div>

<!-- List all challenges -->
<h3>Available Challenges</h3>
<table>
    <tr>
        <th>Title</th>
        <th>Goal</th>
        <th>Type</th>
        <th>Action</th>
    </tr>
    <?php foreach ($challenges as $challenge): ?>
    <tr>
        <td><?= htmlspecialchars($challenge['title']) ?></td>
        <td><?= htmlspecialchars($challenge['goal']) ?></td>
        <td><?= htmlspecialchars($challenge['type']) ?></td>
        <td>
            <form method="POST" style="display:inline-block;">
                <input type="hidden" name="challenge_id" value="<?= $challenge['id'] ?>">
                <button type="submit" name="accept_challenge">Accept</button>
            </form>
            <button onclick="openLeaderboard(<?= $challenge['id'] ?>)">View Leaderboard</button>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<!-- Leaderboard Popup -->
<div id="leaderboardPopup">
    <span class="closeBtn" onclick="closeLeaderboard()">❌</span>
    <h3>Leaderboard</h3>
    <table id="leaderboardTable">
        <thead>
            <tr>
                <th>Rank</th>
                <th>User</th>
                <th>Progress</th>
                <th>Actions</th>
                <th>Cheer</th>
            </tr>
        </thead>
        <tbody>
            <!-- Filled dynamically by JS -->
        </tbody>
    </table>
</div>

<!-- Cheers received -->
<div class="received-cheers">
    <h3>Cheers You Received</h3>
    <?php if (empty($cheers)): ?>
        <p>No cheers yet. Send some cheers to friends!</p>
    <?php else: ?>
        <ul>
        <?php foreach ($cheers as $c): ?>
            <li>
                <strong><?= htmlspecialchars($c['sender_name']) ?>:</strong> 
                <?= htmlspecialchars($c['message']) ?>
                <em>(<?= htmlspecialchars($c['sent_at']) ?>)</em>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<script>
// Open leaderboard popup and submit POST form to reload page with leaderboard
function openLeaderboard(challengeId) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.style.display = 'none';

    const challengeInput = document.createElement('input');
    challengeInput.name = 'challenge_id';
    challengeInput.value = challengeId;
    form.appendChild(challengeInput);

    const leaderboardInput = document.createElement('input');
    leaderboardInput.name = 'view_leaderboard';
    leaderboardInput.value = '1';
    form.appendChild(leaderboardInput);

    document.body.appendChild(form);
    form.submit();
}

function closeLeaderboard() {
    document.getElementById('leaderboardPopup').style.display = 'none';
}

// Fill leaderboard popup on page load if leaderboard data exists
window.onload = function() {
    <?php if (!empty($leaderboard)): ?>
        const leaderboardData = <?= json_encode($leaderboard) ?>;
        const currentUserId = <?= json_encode($current_user_id) ?>;
        const tbody = document.querySelector('#leaderboardTable tbody');
        tbody.innerHTML = ''; // Clear previous

        leaderboardData.forEach((user, index) => {
            const tr = document.createElement('tr');

            // Rank
            const rankTd = document.createElement('td');
            rankTd.textContent = index + 1;
            tr.appendChild(rankTd);

            // User Name
            const userTd = document.createElement('td');
            userTd.textContent = user.name;
            tr.appendChild(userTd);

            // Progress
            const progressTd = document.createElement('td');
            progressTd.textContent = user.progress;
            tr.appendChild(progressTd);

            // Actions: if current user, show "Complete" button to increase progress
            const actionsTd = document.createElement('td');
            if (user.user_id == currentUserId) {
                const form = document.createElement('form');
                form.method = 'POST';

                const challengeInput = document.createElement('input');
                challengeInput.type = 'hidden';
                challengeInput.name = 'challenge_id';
                challengeInput.value = user.challenge_id;
                form.appendChild(challengeInput);

                const markDoneBtn = document.createElement('button');
                markDoneBtn.type = 'submit';
                markDoneBtn.name = 'mark_done';
                markDoneBtn.textContent = 'Complete';
                markDoneBtn.className = 'progressBtn';
                form.appendChild(markDoneBtn);

                actionsTd.appendChild(form);
            } else {
                actionsTd.textContent = '-';
            }
            tr.appendChild(actionsTd);

            // Cheer Form / Label
            const cheerTd = document.createElement('td');
            if (user.user_id != currentUserId) {
                // Form to send cheer
                const form = document.createElement('form');
                form.method = 'POST';

                const receiverInput = document.createElement('input');
                receiverInput.type = 'hidden';
                receiverInput.name = 'receiver_id';
                receiverInput.value = user.user_id;
                form.appendChild(receiverInput);

                const messageInput = document.createElement('input');
                messageInput.type = 'text';
                messageInput.name = 'message';
                messageInput.placeholder = 'Send a cheer!';
                messageInput.className = 'cheerInput';
                messageInput.required = true;
                form.appendChild(messageInput);

                const sendBtn = document.createElement('button');
                sendBtn.type = 'submit';
                sendBtn.name = 'send_cheer';
                sendBtn.textContent = 'Send';
                sendBtn.className = 'cheerBtn';
                form.appendChild(sendBtn);

                cheerTd.appendChild(form);
            } else {
                cheerTd.innerHTML = '<span class="youLabel">You</span>';
            }
            tr.appendChild(cheerTd);

            tbody.appendChild(tr);
        });

        // Show popup
        document.getElementById('leaderboardPopup').style.display = 'block';
    <?php endif; ?>
};
</script>

</body>
</html>
