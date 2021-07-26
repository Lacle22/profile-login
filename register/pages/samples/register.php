<?php
session_start();
require_once('class.user.php');
$user = new USER();

if($user->is_loggedin()!="")
{
	$user->redirect('home.php');
}

if(isset($_POST['btn-signup']))
{
	$ufirst = strip_tags($_POST['txt_ufirst']);
	$ulast = strip_tags($_POST['txt_ulast']);
	$uname = strip_tags($_POST['txt_uname']);
	$umail = strip_tags($_POST['txt_umail']);
	$upass = strip_tags($_POST['txt_upass']);
	
	if($uname=="")	{
		$error[] = "provide username !";	
	}
	else if($umail=="")	{
		$error[] = "provide email id !";	
	}
	else if(!filter_var($umail, FILTER_VALIDATE_EMAIL))	{
	    $error[] = 'Please enter a valid email address !';
	}
	else if($upass=="")	{
		$error[] = "provide password !";
	}
	else if(strlen($upass) < 6){
		$error[] = "Password must be atleast 6 characters";	
	}
	else
	{
		try
		{
			$stmt = $user->runQuery("SELECT user_name, user_email FROM users WHERE user_name=:uname OR user_email=:umail");
			$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
				
			if($row['user_name']==$uname) {
				$error[] = "sorry username already taken !";
			}
			else if($row['user_email']==$umail) {
				$error[] = "sorry email id already taken !";
			}
			else
			{
				if($user->register($ufirst,$ulast,$uname,$umail,$upass)){	
					$user->redirect('sign-up.php?joined');
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>sign up page</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="assets/css/auth.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="row w-100 m-0">
          <div class="content-wrapper full-page-wrapper d-flex align-items-center auth login-bg">
            <div class="card col-lg-4 mx-auto">
              <div class="card-body px-5 py-5">
                <h3 class="card-title text-left mb-3">Register</h3>

                <form>

                    <?php
			                    if(isset($error))
		                      {
			                      	foreach($error as $error)
			                    	{
					           ?>
                        <div class="alert alert-danger">
                           <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                        </div>
                     <?php
                            }
		                	    }
			                      else if(isset($_GET['joined']))
			                      {
				             ?>
                          <div class="alert alert-info">
                            <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully registered <a href='index.php'>login</a> here
                          </div>
                     <?php
		                      	}
		                	?>

                  <div class="row mt-3 form-group">
                    <div class="col-md-6">
                      <label class="labels">Name</label>
                      <input type="text" class="form-control p_input" name="txt_ufirst" placeholder="Name" value=""></div>
                    <div class="col-md-6">
                      <label class="labels">Surname</label>
                      <input type="text" class="form-control p_input" name="txt_ulast" placeholder="Surname"></div>
                 </div>

                  <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control p_input" name="txt_uname" placeholder="Username" value="<?php if(isset($error)){echo $uname;}?>">
                  </div>

                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control p_input" name="txt_umail" placeholder="Email" value="<?php if(isset($error)){echo $umail;}?>">
                  </div>

                  <div class="row mt-3 form-group">
                    <div class="col-md-6">
                      <label class="labels">password</label>
                      <input type="password" class="form-control p_input" name="txt_upass" placeholder="Password" value=""></div>
                    <div class="col-md-6">
                      <label class="labels">Confirm Password</label>
                       <input type="password" class="form-control p_input" value="" placeholder="Confirm Password"></div>
                 </div>

                 <div class="form-group">
                  <label>Phone</label>
                  <input type="phone" class="form-control p_input" value="" placeholder="Phone">
                </div>

                  <div class="row mt-3 form-group">
                    <div class="col-md-6">
                      <label class="labels">Country</label>
                      <input type="text" class="form-control p_input" placeholder="country" value=""></div>
                    <div class="col-md-6">
                      <label class="labels">State/Region</label>
                      <input type="text" class="form-control p_input" value="" placeholder="state"></div>
                 </div>

                  <div class="form-group d-flex align-items-center justify-content-between">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input"> Remember me </label>
                    </div>

                    <a href="#" class="forgot-pass">Forgot password</a>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-block enter-btn" name="btn-signup">Submit</button>
                  </div>

                  <div class="d-flex">
                    <button class="btn btn-facebook col mr-2">
                      <i class="mdi mdi-facebook"></i> Facebook </button>
                    <button class="btn btn-google col">
                      <i class="mdi mdi-google-plus"></i> Google plus </button>
                  </div>
                  <p class="sign-up text-center">Already have an Account?<a href="index.php"> Login</a></p>
                  <p class="terms">By creating an account you are accepting our<a href="#"> Terms & Conditions</a></p>
                </form>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        <!-- row ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../../assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../../assets/js/off-canvas.js"></script>
    <script src="../../assets/js/hoverable-collapse.js"></script>
    <script src="../../assets/js/misc.js"></script>
    <script src="../../assets/js/settings.js"></script>
    <script src="../../assets/js/todolist.js"></script>
    <!-- endinject -->
  </body>
</html>