<?php
    session_start();
    include('vendor/inc/config.php');//get configuration file
    if(isset($_POST['Usr-login']))
    {
      $u_email=$_POST['u_email'];
      $u_pwd=($_POST['u_pwd']);//
      $stmt=$mysqli->prepare("SELECT u_email, u_pwd, u_id FROM tms_user WHERE u_email=? and u_pwd=? ");//sql to log in user
      $stmt->bind_param('ss',$u_email,$u_pwd);//bind fetched parameters
      $stmt->execute();//execute bind
      $stmt -> bind_result($u_email,$u_pwd,$u_id);//bind result
      $rs=$stmt->fetch();
      $_SESSION['u_id']=$u_id;//assaign session to user id
      $_SESSION['login']=$u_email;
      $uip=$_SERVER['REMOTE_ADDR'];
      $ldate=date('d/m/Y h:i:s', time());
      if($rs)
      {//get user logs
        $uid=$_SESSION['u_id'];
        $uemail=$_SESSION['login'];
        $ip=$_SERVER['REMOTE_ADDR'];
        $geopluginURL='http://www.geoplugin.net/php.gp?ip='.$ip;
        $addrDetailsArr = unserialize(file_get_contents($geopluginURL));
        $city = $addrDetailsArr['geoplugin_city'];
        $country = $addrDetailsArr['geoplugin_countryName'];
       
        
         header("location:user-dashboard.php");
         }
        
      else
      {
      #echo "<script>alert('Access Denied Please Check Your Credentials');</script>";
      $error = "Access Denied Please Check Your Credentials";
      }
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>CleanConnect Pro - User Login</title>
    
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" >
    
    <!-- Custom fonts and styles -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/css/sb-admin.css" rel="stylesheet">
    <link href="vendor/css/custom-login.css" rel="stylesheet">
    
    <style>
    body,html{
	margin: 0;
	padding: 0;
	height: 100%;
	background:  !important;
}

.user_card{
	height: 400px;
	width: 350px;
	margin-top: auto;
	margin-bottom: auto;
	background:#ffffff;
	position: relative;
	display: flex;
	justify-content: center;
	flex-direction: column;
	padding: 10px;
	box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
	-webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
	-moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
	border-radius: 5px;
}

.brand_logo_container{
	position: absolute;
	height: 170px; 
	width: 170px;
	top: -75px;
	border-radius: 50%;
	padding: 10px;
	text-align: center;
}

.brand_logo {
    height: 200px;
    width: 200px;
    border-radius: 50%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}


.form_container{
	margin-top: 100px;
}

.login_btn{
	width: 90%;
	background: #272A7B !important;
	color: white !important;
    border-radius: 30px; 
}

.login_btn:focus{
	box-shadow: none !important; 
	outline: 0px !important;
}
.login_container{
	padding: 0 2rem;	
}
.input-group-text{
	background: #CED4D9  !important;
	color: #494A4B   !important;
	border: 0 !important;
	border-radius: 0.25rem 0 0 0.25rem !important;
    height: 100%;
}
.input_user,
.input_pass:focus{
	box-shadow: none !important;
	outline: 0px !important;
}

.custom-checkbox .custom-control-input:checked~.custom-control-label::before{
	background-color: #272A7B !important;
}
        
    </style>
    
</head>
<body class="bg-dark">
<div class="container h-100">
	<div class="d-flex justify-content-center h-100">
		<div class="user_card">
			<div class="d-flex justify-content-center">
				<div class="brand_logo_container">
					<img src="img/logoccp1.png" class="brand_logo" >
				</div>
			</div>
            <div class="card-body">
            <!-- INJECT SWEET ALERT -->
            <?php if(isset($error)) {?>
            <script>
                setTimeout(function() {
                    swal("Failed!", "<?php echo $error;?>", "error");
                }, 100);

            </script>
            <?php } ?>
			<div class="d-flex justify-content-center form_container">
				<form method="POST">
					<div class="input-group mb-3">
						<div class="input-group-append">
							<span class="input-group-text"><i class="fas fa-envelope"></i></span>					
						</div>
						<input type="email" name="u_email" id="inputEmail" class="form-control" required="required" autofocus="autofocus" placeholder="Email address">
					</div>
					<div class="input-group mb-3">
						<div class="input-group-append">
							<span class="input-group-text"><i class="fas fa-lock"></i></span>					
						</div>
						<input type="password" name="u_pwd" id="inputPassword" class="form-control" required="required" placeholder="Password">
					</div>
					<div class="form-group">
						<div class="custom-control custom-checkbox">
							<input type="checkbox" name="rememberme" class="custom-control-input" id="customControlInline">
							<label class="custom-control-label" for="customControlInline">Remember me</label>
						</div>
					</div>
				
			</div>
			<div class="d-flex justify-content-center mt-3 login_container">
				<button type="submit" name="Usr-login" class="btn login_btn">Login</button> 
                
        
			</div>
			</form>
			<div class="mt-4">
				<div class="d-flex justify-content-center links small">
				<a href="usr-register.php" class="ml-2"> <i class="fas fa-user-plus"></i> Sign Up   </a>
				</div>
				<div class="d-flex justify-content-center small">
					<a href="usr-forgot-password.php"><i class="fas fa-key"></i> Forgot Password?</a>
				</div>
			</div>
             
		</div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Inject Sweet Alert JS -->
    <script src="vendor/js/swal.js"></script>

</body>
</html>