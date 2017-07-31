<?php
// Core initialization
require_once "init.php";

// Login checking
if ($session->is_logged_in()) { 
    redirect_to("index.php"); 
}

$submit = get_env('submit');

if ($submit) { // Form has been submitted.
    $username = trim(get_env('username'));
    $password = trim(get_env('password'));
    // Check the database to see if username/password exist.
    $found_user = User::authenticate($username, $password);
    if ($found_user) {
        $session->login($found_user);
        redirect_to("index.php");
    } else {
        $message = "Usename/password combination incorrect.";
    }
} else { // Form has not been submitted.
    $username = "";
    $password = "";
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Boardroom Booker</title>
        <!-- stylesheets -->
        <link href="<?php echo VENDOR_PATH.DS."bootstrap".DS."css".DS; ?>bootstrap.css" rel="stylesheet">
        <link href="<?php echo CSS_PATH; ?>style.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet"> 
  </head>
  <body id="login_page">
    <header id="header">
      <h1>Boardroom Booker</h1>
    </header>
    <section  id="main">
        <h2>User Login</h2>
        <?php echo output_message($message); ?>

            <form action="" method="post">
                <div class="form-group">
                    <label>Username:</label>
                    <input required  minlength="3" maxlength="30" class="form-control"  type="text" name="username" value="<?php echo htmlentities($username); ?>">
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input required  minlength="8" maxlength="30" class="form-control" type="password" name="password" value="<?php echo htmlentities($password); ?>">
                </div>
                <div class="form-group submit">
                    <input type="submit" name="submit" value="Login" class="btn btn-default">
                </div>
            </form>
        
    </section>
    <footer id="footer">Copyright <?php echo date("Y", time()); ?>, Vladimir Dyrda</footer>
    <!-- scripts -->
    <script src="<?php echo JS_PATH; ?>jquery-3.1.1.min.js"></script>
    <script src="<?php echo VENDOR_PATH.DS."bootstrap".DS."js".DS; ?>bootstrap.min.js"></script>
    <script src="<?php echo JS_PATH; ?>jquery.validate.min.js"></script>
    <script>
        jQuery(function($) {
            $(document).ready(function(){
                   $('input[name="username"]').focus();
            });
        });        
    </script>
  </body>
</html>
<?php  if (isset($db)) { 
    $db->close();     
} 
/*
// DEBUG:

$pwd = Password::encrypt('nikolaev');
$sql = "UPDATE user SET password = '{$pwd}'";
$result = $db->query($sql);
if ($db->error) {
    echo "<h4>".$db->error."</h4>";
} else {
    echo "<h4>Password is updated!</h4>";
}
*/
