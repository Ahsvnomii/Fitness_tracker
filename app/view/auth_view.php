<!DOCTYPE html><html><head><title>Login / Signup</title>
<style>
body{font-family:sans-serif;max-width:400px;margin:auto;padding:20px;}
input,select,button{width:100%;margin:8px 0;padding:10px;}
.error{background:#fcc;color:#900;padding:10px;}
</style></head><body>
<h2><?= $showLogin ? " Login" : " Sign Up" ?></h2>
<?php if ($errors): ?>
<div class="error">
    <ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul>
</div>
<?php endif; ?>

<?php if ($showLogin): ?>
<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button name="login">Login</button>
    <p>Don't have an account? <a href="?action=signup">Sign up</a></p>
</form>
<?php else: ?>
<form method="POST">
    <input name="name" placeholder="Full Name" value="<?=htmlentities($_POST['name']??'')?>" required>
    <input name="email" type="email" value="<?=htmlentities($_POST['email']??'')?>" required>
    <input name="age" type="number" min="18" placeholder="Age" value="<?=htmlentities($_POST['age']??'')?>" required>
    <select name="gender" required>
        <option value="">Gender</option>
        <option <?=($_POST['gender']=='male')?'selected':''?> value="male">Male</option>
        <option <?=($_POST['gender']=='female')?'selected':''?> value="female">Female</option>
    </select>
    <input name="password" type="password" placeholder="Password" required>
    <button name="signup">Sign Up</button>
    <p>Don't have an account? <a href="auth.php?action=signup">Sign Up</a></p>

</form>
<?php endif; ?>
</body></html>
