<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
	<meta charset='UTF-8' />
    <title><?php echo isset($title) ? $title : "izCMS"; ?></title>
	
    <!--
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/totem.min.js"></script>
    -->
    <!--
    
    <script type="text/javascript">
    -->
    
    
	<link rel='stylesheet' href='css/style.css' />
</head>

<body>
	<div id="container">
	<div id="header">
		<h1><a href="index.php">izCMS</a></h1>
        	<p class="slogan">The iz Content Management System</p>
	</div>
	<div id="navigation">
		<ul>
            <li><a href='http://localhost/izcms/index.php'>Home</a></li>
			<li><a href='#'>About</a></li>
			<li><a href='#'>Services</a></li>
			<li><a href='contact.php'>Contact us</a></li>
		</ul>
        
        <p class="greeting">Xin chao <?php if (isset($_SESSION['first_name'])) {
            echo $_SESSION['first_name'];
        } else {
            echo "ban";
        }
        ?>
         </p>
	</div><!-- end navigation-->
