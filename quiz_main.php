<?php
session_start();
if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)) {
header ("Location: login.html");
}
if(!isset($_GET['category']) || empty($_GET['category'])){
        header ("Location: take_quiz.php");
}
include("php/config.php");
$db=mysqli_select_db($con,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());

function getQuestions()
	{
                $i = 0;
                $query = mysqli_query($GLOBALS['con'],"SELECT a.* FROM all_questions_details a, all_sections_details b where lower(b.sectionname) = lower('$_GET[category]') order by rand() limit 10");
                while ($row = mysqli_fetch_array($query,MYSQLI_ASSOC)) {
                        $i++;
                        $questions .= '<div class="demo-graphs mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--9-col">'
                  .'<div class="mdl-card__title">'
                  .'<h2 class="mdl-card__title-text">Question ' .$i .'</h2>'
                  .'</div>'
                  .'<div class="mdl-card__supporting-text mdl-color-text--grey-600">'
                  .$row['question_text']
                  .'</div>'
                  .'<div class="mdl-card__supporting-text mdl-color-text--blue-grey-10">'
                  .'<ul style="list-style: none;">'
                  .getOptions($row['questionid'])
                  .'</ul>'
                  .'</div>'
                  .'</div>';
                }
                return $questions;
	}
function getOptions($questionid)
        {
                $i = 0;
                $query = mysqli_query($GLOBALS['con'],"SELECT * FROM `all_answers_details` WHERE questionid=" . $questionid .' order by rand()');
                while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                        $i++;
                        $options .= "<li>"
                    .'<label for="' .$row['answerid'] 
                    .'" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect">'
                    .'<input type="checkbox" id="' .$row['answerid'] .'" class="mdl-checkbox__input" name="ck[' .$questionid . '][' .$row['answerid'] . ']" value="' .$questionid .',' .$row['answerid'] .'">'
                    .'<span class="mdl-checkbox__label">' .$row['answer']
                    .'</span>'
                    .'</label>'
                    .'</li>';
                }
                return $options;
        }
?>

<!DOCTYPE html>
<!--
  Material Design Lite
  Copyright 2015 Google Inc. All rights reserved.

  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

      https://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License
-->
<html lang="en">
	<head>
		<!--script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script-->
                <script type="text/javascript" src="http://code.jquery.com/jquery-3.1.0.min.js"></script>
		<script type="text/javascript" src="scripts/jquery.donutclock.js"></script>
                <!--script type="text/javascript" src="scripts/drawer-right.js"></script-->
		
		<!--[if lte IE 8]>
			<script type="text/javascript" src="http://explorercanvas.googlecode.com/svn/trunk/excanvas.js"></script>
		<![endif]-->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
		<title>GetQuizzed - Take Quiz</title>
		
		<!-- Add to homescreen for Chrome on Android -->
		<meta name="mobile-web-app-capable" content="yes">
		<link rel="icon" sizes="192x192" href="images/android-desktop.png">

		<!-- Add to homescreen for Safari on iOS -->
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="apple-mobile-web-app-title" content="Material Design Lite">
		<link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">

		<!-- Tile icon for Win8 (144x144 + tile color) -->
		<meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
		<meta name="msapplication-TileColor" content="#3372DF">

		<link rel="shortcut icon" href="images/favicon.png">

		<!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
		<!--
		<link rel="canonical" href="http://www.example.com/">
		-->

		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<link rel="stylesheet" href="css/material.cyan-light_blue.min.css">
		<link rel="stylesheet" href="css/styles.css">
                <link rel="stylesheet" href="css/drawer-right.css">
                <style>
                .mdl-button--floating-action {
                        position: fixed;
			display: block;
			right: 0;
			bottom: 0;
			margin-right: 40px;
			margin-bottom: 40px;
			z-index: 900;
                }
                </style>
	</head>

	<body>
                <script type="text/javascript">
                        var isClean = false;
                        var confirmOnPageExit = function (e)
                        {
                                e = e || window.event;
                                var message = 'If you navigate away from this page, you will lose your progress. Would you like to continue navigation?';
                                if(e)
                                {
                                        e.returnValue = message;
                                }
                                
                                if (isClean == false){ return message; }
                        };
                        if (isClean){
                        window.onbeforeunload = null;
                        }
                        else {
                        window.onbeforeunload = confirmOnPageExit;
                        }
                </script>
		<div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
			<header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
				<div class="mdl-layout__header-row">
					<span class="mdl-layout-title">Quiz</span>
                                        <div class="mdl-layout-spacer"></div>
                                        <div class="material-icons mdl-badge" id="notif" style="cursor: pointer;">notifications</div>
				</div>
			</header>
			<div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
				<header class="demo-drawer-header">
					<img src="<?=$_SESSION['profilepic'] ?>" class="demo-avatar">
					<div class="demo-avatar-dropdown">
						<span><?=$_SESSION['username'] ?></span>
						<div class="mdl-layout-spacer"></div>
						<button id="accbtn" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
						<i class="material-icons" role="presentation">arrow_drop_down</i>
						<span class="visuallyhidden">Accounts</span>
						</button>
						<ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="accbtn">
							<li class="mdl-menu__item">Update Profile Info</li>
							<li><a class="mdl-menu__item" href="php/signout.php">Sign Out</a></li>
							<!--li class="mdl-menu__item"><i class="material-icons">add</i>Add another account...</li-->
						</ul>
					</div>
				</header>
				<nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
					<a class="mdl-navigation__link" href="homepage.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">home</i>Home</a>
					<a class="mdl-navigation__link" href="take_quiz.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">school</i>Take Quiz</a>
					<div class="mdl-layout-spacer"></div>
					<a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">help_outline</i>Help</a>
				</nav>
			</div>
                        <div class="mdl-layout__drawer-right">
                                <span class="mdl-layout-title">Summary</span>
                                <div class="drawer-text" style="padding-left: 20px;">
                                        Total Questions: 10
                                </div>
                                <div class="drawer-text" style="padding-left: 20px;">
                                        Answered: 0
                                </div>
                                <div class="drawer-text" style="padding-left: 20px;">
                                        Remaining: 10
                                </div>
                                <a id="view-source">
                                        <div id="donutclock" start-time="20" style="align:centre;"></div>
                                </a>
                        </div>
                        
                        <main class="mdl-layout__content mdl-color--grey-100">
                        <form action="php/display_results.php" method="post">
                                <div class="mdl-grid demo-content">
                                        <?php echo getQuestions(); ?>
                                </div>
                                <input type="submit" name="submit" Value="Submit" class="mdl-button--floating-action mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored"/>
                        </form>
                        </main>
                        <div class="mdl-layout__obfuscator-right"></div>
                </div>
		<script>
			$("#donutclock").donutclock({'size': 200 });
			$("#donutclock").donutclock("animate");
		</script>
		<script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
                <script>
                $('.mdl-layout__obfuscator-right').click(function(){
 if($('.mdl-layout__drawer-right').hasClass('active')){       
    $('.mdl-layout__drawer-right').removeClass('active'); 
 }
 else{
    $('.mdl-layout__drawer-right').addClass('active'); 
 }
});
$('#notif').click(function(){
 if($('.mdl-layout__drawer-right').hasClass('active')){       
    $('.mdl-layout__drawer-right').removeClass('active'); 
 }
 else{
    $('.mdl-layout__drawer-right').addClass('active'); 
 }
});
                </script>
	</body>
</html>