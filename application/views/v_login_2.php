<!DOCTYPE html>
<html lang="en">
<head>
	<title><?=SISTEM_NAME?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="<?=base_url("assets/Login_v1")?>/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/Login_v1")?>/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/Login_v1")?>/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/Login_v1")?>/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/Login_v1")?>/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/Login_v1")?>/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/Login_v1")?>/css/util.css">
	<link rel="stylesheet" type="text/css" href="<?=base_url("assets/Login_v1")?>/css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="<?=base_url("assets")?>/images/logors.png" alt="IMG">
				</div>

				<form class="login100-form validate-form" autocomplete="off">
					<span class="login100-form-title">
						Login To <?=SISTEM_NAME?>
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Username is required">
						<input class="input100" type="text" name="user_name" placeholder="username" required>
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password" name="user_password" placeholder="Password" required>
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Login
						</button>
					</div>
					<div class="text-center p-t-12">
						<a class="txt2" href="<?=base_url("antrean_recipe")?>">
							Antrean Resep
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
					<div class="text-center p-t-136">
						<a class="txt2" href="#">
							<?=FOOT_NOTE?>
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="<?=base_url("assets/Login_v1")?>/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="<?=base_url("assets/Login_v1")?>/vendor/bootstrap/js/popper.js"></script>
	<script src="<?=base_url("assets/Login_v1")?>/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="<?=base_url("assets/Login_v1")?>/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="<?=base_url("assets/Login_v1")?>/vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
        $('form').submit(function(){
            $.post("login/cek_login",$(this).serialize(),function(resp){
                if (resp.is_error === "true") {
                    alert(resp.message);
                }else{
                    window.location.assign(resp.redirect);
                }
            },'json');
            return false;
        });
	</script>
<!--===============================================================================================-->
	<script src="<?=base_url("assets/Login_v1")?>/js/main.js"></script>

</body>
</html>