
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login-style.css">
    <title>Login</title>
</head>
<body>
    <div class="gradient-background">
<?php
        if(isset($_GET['notif'])){
            $message = $_GET['notif'];
            echo "<h3>" . $message . "<h3>";
        }
    ?>
    <div class="header">
        <div class="logo-header">
            <img src="LOGO-TUPV-CONNECT.png">
            <div class="login-form">
                <form action="authenticate.php" method="POST">
                    <input type="text" name="login_id" placeholder="TUPV ID Number">
                    <input type="password" name="password" placeholder="Password">
                    <button type="submit" name="login">Log In</button>
                </form>
            </div>
        </div>
    </div>
    <div class="signup">
        <h2>Create a new account</h2>
        <form action="create-account.php" method="POST">
            <input type="text" name="first_name" placeholder="First name">
            <input type="text" name="last_name" placeholder="Last name">
            <input type="text" name="ID_Number" placeholder="TUPV ID Number">
            <input type="text" name="Course-Year&section" placeholder="Course and Section">
            <input type="password" name="new_password" placeholder="New Password">
            <label for="birthday">Birthday</label>
            <input type="date" name="birthday">
            <label for="gender">Gender</label>
            <select name="gender">
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
            <button type="submit" name="create">Create Account</button>
        </form>
    </div>
    </div>
</body>
</html>
