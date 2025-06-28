<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <style>
        body { font-family: sans-serif; padding: 30px; max-width: 500px; margin: auto; }
        input, select, button { width: 100%; padding: 10px; margin-top: 10px; }
        .error { background: #fdd; padding: 10px; color: darkred; margin-top: 10px; }
        h2 { text-align: center; color: #007BFF; }
    </style>
</head>
<body>
    <h2>Create Your Account</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>

        <input type="email" name="email" placeholder="Email Address" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

        <input type="number" name="age" placeholder="Age (18+)" value="<?= htmlspecialchars($_POST['age'] ?? '') ?>" min="18" required>

        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="male" <?= ($_POST['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
            <option value="female" <?= ($_POST['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
        </select>

        <input type="password" name="password" placeholder="Password (min 6 chars)" required>

        <button type="submit">Sign Up</button>
    </form>
</body>
</html>
