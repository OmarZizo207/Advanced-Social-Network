<?php
	include('core/init.php');

	$user_id = $_SESSION['user_id'];
	$user    = $getFromU->userData($user_id);
	$notify  	 = $getFromM->getNotificationCount($user_id);

	if($getFromU->loggedIn() === false)
	{
		header("Location: ". BASE_URL ."index.php");
	}

	if(isset($_POST['submit']))
	{
		$username = $getFromU->checkInput($_POST['username']);
		$email    = $getFromU->checkInput($_POST['email']);
		$error    = array();
		if(!empty($username) AND !empty($email))
		{
			if($user->user_name != $username AND $getFromU->checkUsername($username) === true)
			{
				$error['username'] = "The Username is not available !";
			}
			else if(preg_match("/[^a-zA-Z0-9\!]", $username))
			{
				$error['username'] = "Only characters and numbers allowed!";
			}
			else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$error['email'] = "Invalid Email Format!";
			}
			else if($user->email != $email AND $getFromU->checkEmail($email) === true)
			{
				$error['email'] = "Email already in use!";
			}
			else
			{
				$getFromU->update('users', $user_id, array('email' => $email, 'user_name' => $username));
				header("Location: ".BASE_URL."settings/account");
			}
		}
		else
		{
			$error['fields'] = 'All Fields are required!';
		}
	}
?>

<html>
	<head>
		<title>Account settings page</title>
		<meta charset="UTF-8" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css"/>
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
		<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style-complete.css"/>
		<link rel="shortcut icon" href="assets/images/icon/icons8-twitter-48.png">
	</head>
	<!--Helvetica Neue-->
<body>
<div class="wrapper">
<!-- header wrapper -->
<div class="header-wrapper">

<div class="nav-container">
  <!-- Nav -->
   <div class="nav">
		 <div class="nav-left">
			<ul>
				<li><a href="<?php echo BASE_URL; ?>home.php"><i class="fa fa-home" aria-hidden="true"></i>Home</a></li>
 			<?php if($getFromU->loggedIn() === true) { ?>
				<li><a href="<?php echo BASE_URL;?>i/notifications"> <i class="fa fa-bell" aria-hidden="true"> </i> Notifications <span id="notification"> <?php if($notify->totalN > 0){echo '<span class="span-i">'.$notify->totalN.'</span>';} ?> </span> </a> </li>
				<li id="messagePopup"><i class="fa fa-envelope" aria-hidden="true"></i>Messages<span id="messages"><?php if($notify->totalMessages > 0){echo '<span class="span-i">'.$notify->totalMessages.'</span>';} ?></span></li>
			<?php } ?> 
 			</ul>
		</div>
		<!-- nav left ends-->
		<div class="nav-right">
			<ul>
				<li><input type="text" placeholder="Search" class="search"/><i class="fa fa-search" aria-hidden="true"></i></li>
				<div class="nav-right-down-wrap">
					<ul class="search-result">
					
					</ul>
				</div>
 				<li class="hover"><label class="drop-label" for="drop-wrap1"><img src="<?php echo BASE_URL.$user->profileImage;?>"/></label>
				<input type="checkbox" id="drop-wrap1">
				<div class="drop-wrap">
					<div class="drop-inner">
						<ul>
							<li><a href="<?php echo BASE_URL.$user->user_name;?>"><?php echo $user->user_name;?></a></li>
							<li><a href="<?php echo BASE_URL; ?>settings/account">Settings</a></li>
							<li><a href="<?php echo BASE_URL; ?>includes/logout.php">Log out</a></li>
						</ul>
					</div>
				</div>
				</li>
				<li><label for="pop-up-tweet" class="addTweetBtn">Tweet</label></li>

			</ul>
		</div>
		<!-- nav right ends-->
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/popupForm.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/hashtag.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/search.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/delete.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/messages.js"></script>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/notification.js"></script>


 
	</div>
	<!-- nav ends -->

</div><!-- nav container ends -->
</div><!-- header wrapper end -->
		
	<div class="container-wrap">

		<div class="lefter">
			<div class="inner-lefter">

				<div class="acc-info-wrap">
					<div class="acc-info-bg">
						<!-- PROFILE-COVER -->
						<img src="<?php echo BASE_URL.$user->profileCover;?>"/>  
					</div>
					<div class="acc-info-img">
						<!-- PROFILE-IMAGE -->
						<img src="<?php echo BASE_URL.$user->profileImage;?>"/>
					</div>
					<div class="acc-info-name">
						<h3><?php echo $user->screenName;?></h3><br>
						<span><a href="<?php echo BASE_URL.$user->user_name;?>">@<?php echo $user->user_name;?></a></span>
					</div>
				</div><!--Acc info wrap end-->

				<div class="option-box">
					<ul> 
						<li>
							<a href="<?php echo BASE_URL;?>settings/account" class="bold">
							<div>
								Account
								<span><i class="fa fa-angle-right" aria-hidden="true"></i></span>
							</div>
							</a>
						</li>
						<li>
							<a href="<?php echo BASE_URL;?>settings/password">
							<div>
								Password
								<span><i class="fa fa-angle-right" aria-hidden="true"></i></span>
							</div>
							</a>
						</li>
					</ul>
				</div>

			</div>
		</div><!--LEFTER ENDS-->
		
		<div class="righter">
			<div class="inner-righter">
				<div class="acc">
					<div class="acc-heading">
						<h2>Account</h2>
						<h3>Change your basic account settings.</h3>
					</div>
					<div class="acc-content">
					<form method="POST">
						<div class="acc-wrap">
							<div class="acc-left">
								USERNAME
							</div>
							<div class="acc-right">
								<input type="text" name="username" value="<?php echo $user->user_name; ?>"/>
								<span>
									<!-- Username Error -->
									<?php if(isset($error['username'])){echo $error['username']; } ?>
								</span>
							</div>
						</div>

						<div class="acc-wrap">
							<div class="acc-left">
								Email
							</div>
							<div class="acc-right">
								<input type="text" name="email" value="<?php echo $user->email; ?>"/>
								<span>
									<!-- Email Error -->
									<?php if(isset($error['email'])){echo $error['email']; } ?>
								</span>
							</div>
						</div>
						<div class="acc-wrap">
							<div class="acc-left">
								
							</div>
							<div class="acc-right">
								<input type="Submit" name="submit" value="Save changes"/>
							</div>
							<div class="settings-error">
								<!-- Fields Error -->
								<?php if(isset($error['fields'])){echo $error['fields']; } ?>
   							</div>	
						</div>
					</form>
					</div>
				</div>
				<div class="content-setting">
					<div class="content-heading">
						
					</div>
					<div class="content-content">
						<div class="content-left">
							
						</div>
						<div class="content-right">
							
						</div>
					</div>
				</div>
			</div>	
		</div><!--RIGHTER ENDS-->
		<div class="popupTweet"></div>
		<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/postMessages.js"></script>		

	</div>
	<!--CONTAINER_WRAP ENDS-->

	</div><!-- ends wrapper -->
</body>

</html>

