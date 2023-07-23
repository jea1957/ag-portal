<?php

$session_bypass_login = true;
require_once __DIR__ . '/src/require_all.php';
require_once __DIR__ . '/src/accounts.php';

// form_state: 'login', 'forgot_pw', 'change_pw'
// submit in 'login' state: 'login', 'forgot_pw'
// submit in 'forgot_pw' state: 'send' 'back'
// submit in 'change_pw' state: 'change'
// $_SESSION['form_msg'] is used to transfer message between form states.

if (!isset($_SESSION['form_state'])) {
    $_SESSION['form_state'] = 'login'; // Default state
}

if (isset($_POST['submit'])) { // Handle submit from this page
    try {
        if ($_SESSION['form_state'] == 'login') {
            if ($_POST['submit'] == 'login') {
                if (isset($_POST['email']) && isset($_POST['password'])) {
                    $accounts = new Accounts($pdo);
                    $email = filter_var(clean_input($_POST['email']), FILTER_SANITIZE_EMAIL);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $form_err_email = _L('invalid_email');
                        goto retry;
                    }
                    $password = clean_input($_POST['password']);
                    if (!password_ok($password)) {
                        $form_err_password = _L('invalid_password');
                        goto retry;
                    }

                    $result = $accounts->getByEmail($email);
                    if (!isset($result)) {
                        $form_err = _L('invalid_account');
                        goto retry;
                    }
                    if ($result->state == 3)  {
                        $form_err = _L('login_disabled');
                        goto retry;
                    }
                    if (!password_verify($password, $result->password)) {
                        $form_err = _L('invalid_account');
                        goto retry;
                    }

                    $_SESSION['account_id']       = $result->accountid;
                    $_SESSION['account_name']     = $result->name;
                    $_SESSION['account_email']    = $result->email;
                    $_SESSION['account_state']    = $result->state;
                    $_SESSION['account_role']     = $result->role;
                    $_SESSION['account_lang']     = $result->lang;

                    if ($result->state == 1) {
                        $_SESSION['form_msg'] = _L('change_pw_update');
                        $_SESSION['form_state'] = 'change_pw';
                        goto retry;
                    }
                    $_SESSION['authenticated'] = true;
                    $accounts->update_activity($result->accountid);
                    unset($_SESSION['form_state']);
                    unset($_SESSION['form_msg']);
                    switch($result->role) {
                        case 1: // Superman
                            $_SESSION['role_admin']    = true;
                            $_SESSION['role_update']   = true;
                            $_SESSION['role_mail']     = true;
                            break;
                        case 2: // Board
                            $_SESSION['role_admin']    = false;
                            $_SESSION['role_update']   = true;
                            $_SESSION['role_mail']     = true;
                            break;
                        case 3: // Caretaker
                            $_SESSION['role_admin']    = false;
                            $_SESSION['role_update']   = false;
                            $_SESSION['role_mail']     = false;
                            break;
                        case 4: // Administration
                            $_SESSION['role_admin']    = false;
                            $_SESSION['role_update']   = false;
                            $_SESSION['role_mail']     = false;
                            break;
                        case 5: // Tester
                            $_SESSION['role_admin']    = false;
                            $_SESSION['role_update']   = false;
                            $_SESSION['role_mail']     = false;
                            break;
                        default:
                            $_SESSION['role_admin']    = false;
                            $_SESSION['role_update']   = false;
                            $_SESSION['role_mail']     = false;
                            break;
                    }
                    error_log("Login: $email");
                    //error_log('Session: ' . print_r($_SESSION, 1));
                } else { // Invalid POST
                    http_response_code(404);
                    require_once __DIR__ . '/src/404.php';
                    exit;
                }
            } elseif ($_POST['submit'] == 'forgot_pw') {
                $_SESSION['form_state'] = 'forgot_pw';
            }
        } elseif ($_SESSION['form_state'] == 'forgot_pw') {
            if ($_POST['submit'] == 'send') {
                if (isset($_POST['email'])) {
                    $accounts = new Accounts($pdo);
                    $email = filter_var(clean_input($_POST['email']), FILTER_SANITIZE_EMAIL);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $form_err_email = _L('invalid_email');
                        goto retry;
                    }
                    $result = $accounts->getByEmail($email);
                    if (!isset($result)) {
                        $form_err_email = _L('forgot_pw_unknown');
                        goto retry;
                    }
                    $name = $result->name;
                    $password = random_password(8);
                    $accounts->set_password($email, $password, 1); // Set state to FirstLogin(1)
                    $body_html = sprintf(_L('pwm_body'), $name, $db_url, $email, $password);

                    $result = send_email($db_contact, $db_cname, $email, $name, _L('pwm_subject'), $body_html);
                    if (isset($result['error'])) {
                        $form_err = _L('forgot_pw_not_sent');
                    } else {
                        $_SESSION['form_msg'] = _L('forgot_pw_sent');
                    }
                    $_SESSION['form_state'] = 'login';
                } else { // Invalid POST
                    http_response_code(404);
                    require_once __DIR__ . '/src/404.php';
                    exit;
                }
            } elseif ($_POST['submit'] == 'back') {
                $_SESSION['form_state'] = 'login';
            }
        } elseif ($_SESSION['form_state'] == 'change_pw') {
            if ($_POST['submit'] == 'change') {
                if (isset($_POST['password']) && isset($_POST['password2']) && isset($_POST['password3'])) {
                    $password = clean_input($_POST['password']);
                    $password2 = clean_input($_POST['password2']);
                    $password3 = clean_input($_POST['password3']);
                    if (!password_ok($password)) {
                        $form_err_password = _L('invalid_password');
                        goto retry;
                    }
                    if (!password_ok($password2)) {
                        $form_err_password2 = _L('invalid_password');
                        goto retry;
                    }
                    if (!password_ok($password3)) {
                        $form_err_password3 = _L('invalid_password');
                        goto retry;
                    }
                    if (strcmp($password, $password2) == 0) {
                        $form_err_password2 = _L('change_pw_same');
                        goto retry;
                    }
                    if (strcmp($password2, $password3) != 0) {
                        $form_err_password3 = _L('change_pw_diff');
                        goto retry;
                    }
                    $accounts = new Accounts($pdo);
                    $result = $accounts->getById($_SESSION['account_id']);
                    if (!isset($result)) {
                        $form_err = _L('invalid_account');
                        $_SESSION['form_state'] = 'login';
                        goto retry;
                    }
                    if (!password_verify($password, $result->password)) {
                        $form_err_password = _L('change_pw_wrong');
                        goto retry;
                    }
                    $accounts->set_password($result->email, $password2, 2); // Set state to Enabled(2)
                    $_SESSION['form_msg'] = _L('login_new_pw');
                    $_SESSION['form_state'] = 'login';
                    $_POST['password'] = $password2; // Save user from typing new password
                } else { // Invalid POST
                    http_response_code(404);
                    require_once __DIR__ . '/src/404.php';
                    exit;
                }
            }
        }
    } catch (PDOException $e) {
        http_response_code(500);
        header("Content-Type: application/json");
        echo json_encode($e->getMessage());
    }
}

if (isset($_SESSION['authenticated'])) {
    header('Location: dashboard.php');
    exit;
}

retry:

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="description" content="<?php echo $db_owner ?>">
  <meta name="author" content="<?php echo $db_author ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $db_owner ?></title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css"
        integrity="sha512-T584yQ/tdRR5QwOpfvDfVQUidzfgc2339Lc8uBDtcp/wYu80d7jwBgAxbyMh0a9YM9F8N3tdErpFI8iaGx6x5g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

<!--
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css"
        integrity="sha512-GQGU0fMMi238uA+a/bdWJfpUGKUkBdgfFdgBm72SUQ6BeyWjoY/ton0tEjH+OSH9iP4Dfh+7HM0I9f5eR0L/4w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
-->
</head>

<body>
  <div class ="container vh-100">
    <div class="row h-100 justify-content-center align-items-center">
      <div class="col-xl-5 col-lg-6 col-md-8 col-sm-10 mx-auto text-center p-4 border">
        <img class="mb-4" src="../favicon.ico" alt="" width="72" height="72">
        <div class="px-2">
          <form method="POST">
            <strong ><?php echo isset($_SESSION['form_msg']) ? $_SESSION['form_msg'].'<br><br>' : '' ?></strong>
            <strong style="color:red"><?php echo isset($form_err) ? $form_err.'<br><br>' : '' ?></strong>

<?php if ($_SESSION['form_state'] == 'login') { ?>

            <div class="form-group mb-3">
              <label for="inputEmail" class="sr-only visually-hidden">Email</label>
              <input type="email" class="form-control" name="email" value="<?php echo $_POST['email'] ?? '' ?>"
                  id="inputEmail" placeholder="<?php echo L('login_email') ?>" maxLength="64" required>
              <strong style="color:red"><?php echo isset($form_err_email) ? $form_err_email : '' ?></strong>
            </div>
            <div class="form-group mb-3">
              <label for="inputPassword" class="sr-only visually-hidden">Password</label>
              <input type="password" class="form-control" name="password" value="<?php echo $_POST['password'] ?? '' ?>"
                  id="inputPassword" placeholder="<?php echo L('login_password') ?>" minLength="8" maxLength="32" required>
              <strong style="color:red"><?php echo isset($form_err_password) ? $form_err_password : '' ?></strong>
            </div>
            <button type="submit" class="btn btn-primary mb-2" name="submit" value="login"><?php echo L('login_login') ?></button>
            <button type="submit" class="btn btn-secondary mb-2" name="submit" value="forgot_pw" formnovalidate><?php echo L('login_forgot') ?></button>

<?php } elseif ($_SESSION['form_state'] == 'forgot_pw') { ?>

            <div class="form-group mb-3">
              <label for="inputEmail" class="sr-only visually-hidden">Email</label>
              <input type="email" class="form-control" name="email" value="<?php echo $_POST['email'] ?? '' ?>"
                  id="inputEmail" placeholder="<?php echo L('login_email') ?>" maxLength="64" required>
              <strong style="color:red"><?php echo isset($form_err_email) ? $form_err_email : '' ?></strong>
            </div>
            <button type="submit" class="btn btn-primary mb-2" name="submit" value="send"><?php echo L('forgot_pw_continue') ?></button>
            <button type="submit" class="btn btn-secondary mb-2" name="submit" value="back" formnovalidate><?php echo L('forgot_pw_back') ?></button>

<?php } elseif ($_SESSION['form_state'] == 'change_pw') { ?>

            <div class="form-group mb-3">
              <label for="inputPassword" class="sr-only visually-hidden">Password</label>
              <input type="password" class="form-control" name="password" value="<?php echo $_POST['password'] ?? '' ?>"
                  id="inputPassword" placeholder="<?php echo L('change_pw_current') ?>" minLength="8" maxLength="32" required>
              <strong style="color:red"><?php echo isset($form_err_password) ? $form_err_password : '' ?></strong>
            </div>
            <div class="form-group mb-3">
              <label for="inputPassword2" class="sr-only visually-hidden">Password</label>
              <input type="password" class="form-control" name="password2" value="<?php echo $_POST['password2'] ?? '' ?>"
                  id="inputPassword2" placeholder="<?php echo L('change_pw_new') ?>" minLength="8" maxLength="32" required>
              <strong style="color:red"><?php echo isset($form_err_password2) ? $form_err_password2 : '' ?></strong>
            </div>
            <div class="form-group mb-3">
              <label for="inputPassword3" class="sr-only visually-hidden">Password</label>
              <input type="password" class="form-control" name="password3" value="<?php echo $_POST['password3'] ?? '' ?>"
                  id="inputPassword3" placeholder="<?php echo L('change_pw_repeat') ?>" minLength="8" maxLength="32" required>
              <strong style="color:red"><?php echo isset($form_err_password3) ? $form_err_password3 : '' ?></strong>
            </div>
            <button type="submit" class="btn btn-primary mb-2" name="submit" value="change"><?php echo L('change_pw_continue') ?></button>
            <input type="hidden" name="email" value="<?php echo $_POST['email'] ?? '' ?>">

<?php } ?>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
                                                                                                  
