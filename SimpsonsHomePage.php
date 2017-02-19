#!/usr/local/bin/php
<html>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<head>
    <title>The Simpsons Archive</title>
	<meta name="keywords" content="The Simpsons Archive, homepage">
	<meta name="description" content="The Simpsons Archive Database">
	<meta name="author" content="Kimberly Branch, Timmy Chandy, William Posey, Matt Weingarten">
	<meta name="copyright" content="Copyright &copy 2017, All Rights Reserved">
    <style>
    <style style="text/css">
    html {
        overflow-y: scroll
    }
    
    .banner {
        height: 40%;
        width: 100%;
    }
    
    .wrapper {
        width: 100%;
        overflow: hidden;
    }
    .container {
        width: 100%;
        margin: 0 auto;
    }
    .banner-img {
        width: 100%;
        border: 5px solid black;
    }
    .page {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .header {
        float: left;
        width: 100%;
    }
    body {
        background-color: black;
    }

    /* simpsons style font */
    @font-face {
        font-family: 'simpsonsFont';
        src:    url('fonts/Simpsonfont.eot');
        src:    url('fonts/Simpsonfont..eot?#iefix') format('embedded-opentype'),
                url('fonts/Simpsonfont.ttf') format('truetype'),
                url('fonts/Simpsonfont.woff') format('woff'),
                url('fonts/Simpsonfont.otf') format('opentype'),
                url('fonts/Simpsonfont.svg') format('svg');
        font-weight: bold;
        font-style: normal;
    }
    /*  this was used to create "Archive" in the banner image,
        left it here in case we wanted to use it for something else (mostly for the shadowing)
     
    .title {
        top: 26%;
        left: 15%;
        font-family: 'simpsonsFont';
        font-size: 85;
        font-weight: bold;
        width: 50%;
        color: rgb(255,255,30);
        position: absolute;
        z-index: 2;
        text-shadow: -8px 4px 1px black,
                     -7px 5px 1px black,
                     -3px 5px 1px black,
                     0px 5px 1px black,
                     0px 4px 1px black,
                     0px 3px 1px black,
                     0px -3.5px 1px black,
                     0px -3.5px 1px black,
                     5px 0px 1px black,
                     -4px 4px 1px black,
                     -4px -2px 1px black;
    }*/
    .navbar {
        margin-bottom: 0;
    }
    h1 {
    	font-family: 'simpsonsFont';
	color:rgb(255,255,30);
	text-align: center;
	font-size: 26px;
        font-weight: bold;
        text-shadow: -8px 4px 1px black,
                     -7px 5px 1px black,
                     -3px 5px 1px black,
                     0px 5px 1px black,
                     0px 4px 1px black,
                     0px 3px 1px black,
                     0px -3.5px 1px black,
                     0px -3.5px 1px black,
                     5px 0px 1px black,
                     -4px 4px 1px black,
                     -4px -2px 1px black;
	padding-bottom: 15px;
    }
    p {
	margin-right: 100px;
	margin-left: 100px;
	margin-bottom: 20px;
	align: center;
	font-family: 'simpsonsFont';
	color:rgb(255,255,30);
        text-shadow: -8px 4px 1px black,
                     -7px 5px 1px black,
                     -3px 5px 1px black,
                     0px 5px 1px black,
                     0px 4px 1px black,
                     0px 3px 1px black,
                     0px -3.5px 1px black,
                     0px -3.5px 1px black,
                     5px 0px 1px black,
                     -4px 4px 1px black,
                     -4px -2px 1px black;
    } 
    p.groove {border-style: groove;} 
    .navbar-nav {
        float:none;
        margin-bottom: 0;
        display: inline-block;;
        text-align: center;
    }
    .dropdown:hover .dropdown-menu {
        display: block;
        margin-top: 0;
    }
    
    nav ul li a {
        color: white !important;
        font-size: 200% !important;
    }
    nav ul ul li a {
        color: black !important;
        font-size: 200% !important;
    }
    .navbar-nav > li {
        display: inline-block;
        float: none;
        font-weight: bold;
    }    
    .navbar .navbar-collapse {
        text-align: center;
    }   
    .navbar-nav > li:dropdown {
        background: white;
    }
    .navbar-nav > .active > a {
        color: yellow !important;
        text-decoration: underline !important;
    }
    #summary{
	display:block;
	padding:0;
	border:100px;
	text-align:center;
	font-size:18px;
    }
    </style>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript">
    // this sets the class to "active" of the currently active link in the navbar
    $(function() {
        $('.nav li').on('click', function() {
            $('.nav li').removeClass('active');
            $(this).addClass('active');
        });
    });
    </script>

</head>

<body>

<div class ="page">
    <div class ="header">
        <div id="banner">
            <div id="wrapper">
                <div id="container">
                        <img class="banner-img" src="banner.PNG" alt="Simpsons Image"/>
                </div>
            </div>
        </div>

        <nav class="navbar navbar-inverse" style="background-color: black;">
        <div class="container-fluid">
            <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>                        
            </button>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="SimpsonsRetrieval.php">Retrieval</a></li>
                    <li><a href="#">Explore</a></li> 
                    <li><a href="SimpsonsTeam.php">Team</a></li> 
                    <li><a href="SimpsonsInspiration.php">Inspiration</a></li> 
                </ul>
            </div>
        </div>
        </nav>
    </div>

    <div id ="summary">
	<table align="center">
		<tr>	
			<td> <div><h1>Welcome to The Simpsons Archive!</h1></div> </td>	
		</tr>	
		<tr>
			<td> 
				<div id=summary>
					<p>This databases project will create a webpage that will contain a comprehensive database of everything related to The Simpsons, </p>
					<p>including character, location, and episode information. Not only can the user retrieve information for everything and everyone </p>
					<p>who has been on the show at some point, but they can also perform many statistical queries to see how Springfield </p>
					<p>has changed in its nearly 30 years of fictional existence. We hope you enjoy. D'oh! </p>
					<br/>
					<hr>
				</div>
			</td>		
				
		</tr>
			
	</table>
    </div>
</div>

</body>
</html>
