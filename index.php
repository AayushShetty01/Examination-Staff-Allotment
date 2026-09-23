<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/db.php';
$db = db();

if (isset($_SESSION['first_name'], $_SESSION['last_name'], $_SESSION['email'])) {
    header('Location: ../home/index.php');
    exit;
}

$_SESSION['login_message'] = '';
$_SESSION['register_message'] = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['submit'] ?? '';

    if ($action === 'register') {
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $confirmPassword = (string)($_POST['cpassword'] ?? '');

        if ($firstName === '' || $lastName === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['register_message'] = '*Enter valid details in all fields.';
        } elseif (strlen($password) < 8) {
            $_SESSION['register_message'] = '*Password must contain at least 8 characters.';
        } elseif ($password !== $confirmPassword) {
            $_SESSION['register_message'] = '*Passwords do not match.';
        } else {
            $check = $db->prepare('SELECT _id FROM users WHERE email = ? LIMIT 1');
            $check->bind_param('s', $email);
            $check->execute();

            if ($check->get_result()->num_rows > 0) {
                $_SESSION['register_message'] = '*An account with this email already exists.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare('INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)');
                $stmt->bind_param('ssss', $firstName, $lastName, $email, $hash);
                $stmt->execute();

                session_regenerate_id(true);
                $_SESSION['first_name'] = $firstName;
                $_SESSION['last_name'] = $lastName;
                $_SESSION['email'] = $email;
                header('Location: ../home/index.php');
                exit;
            }
        }
    } elseif ($action === 'login') {
        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');

        $stmt = $db->prepare('SELECT _id, first_name, last_name, email, password FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        $valid = false;
        if ($user) {
            $valid = password_verify($password, $user['password']);
            // Upgrade legacy double-MD5 records the first time the user logs in.
            if (!$valid && hash_equals((string)$user['password'], md5(md5($password)))) {
                $valid = true;
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $upgrade = $db->prepare('UPDATE users SET password = ? WHERE _id = ?');
                $upgrade->bind_param('si', $hash, $user['_id']);
                $upgrade->execute();
            }
        }

        if ($valid) {
            session_regenerate_id(true);
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['email'] = $user['email'];
            header('Location: ../home/index.php');
            exit;
        }

        $_SESSION['login_message'] = '*Invalid email or password.';
    }
}
?>


<html>

<head>
  <title>VES-ESAS</title>
  <link rel="stylesheet" type="text/css" href="index.css" />
  <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">
  <script type="text/javascript" src="index.js"></script>
</head>

<body>
  <!-- header component -->
  <div class="gen_header">
    
    <div class="header_img"> 
      <img src="ves-logo.png" />
    </div>
    
    <div class="header_title">
      <a href="index.php">VESIT - ESAS</a>
      <hr>
      <div class="header_subtitle">VESIT Examination Staff Allotment System</div>
    </div>

  </div>
  <br />
  <!-- content body -->
  <div class="form_body">
    
    <div class="forms">

      <form method="POST" action="index.php">

        <div class="login_form">

          <div class="form_heading">
            Login Here!
          </div>

          <div class="label">
            Email Address:
          </div>
          <div>
            <input type="text" name="email" placeholder="E-mail Address" />
          </div>

          <div class="label">
            Password:
          </div>
          <div>
            <input type="password" name="password" placeholder="Password" />
          </div>

          <div class="error_message">
            <?= $_SESSION['login_message'] ?>
          </div>

          <div class="submit">
            <button type="submit" name="submit" value="login">LOGIN</button>
          </div>

        </div>
      
      </form>
      
      <form method="POST" action="index.php" name="registerForm" onsubmit="return validateRegisterForm();">

        <div class="register_form">

          <div class="form_heading">
            Register Here!
          </div>

          <div class="label">
            First Name:
          </div>
          <div>
            <input type="text" name="first_name" placeholder="First Name" />
          </div>

          <div class="label">
            Last Name:
          </div>
          <div>
            <input type="text" name="last_name" placeholder="Last Name" />
          </div>

          <div class="label">
            Email Address:
          </div>
          <div>
            <input type="text" name="email" placeholder="E-mail Address" />
          </div>

          <div class="label">
            Password:
          </div>
          <div>
            <input type="password" name="password" placeholder="Password" />
          </div>

          <div class="label">
            Confirm Password:
          </div>
          <div>
            <input type="password" name="cpassword" placeholder="Confirm Password" />
          </div>

          <div class="error_message">
            <?= $_SESSION['register_message'] ?>
          </div>

          <div class="submit">
            <button type="submit" name="submit" value="register">REGISTER</button>
          </div>

        </div>

      </form>
      
    </div>

  </div>

</body>

</html>
