#!/usr/local/bin/php
<html>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<head>
    <title>Inspiration</title>
	<meta name="keywords" content="The Simpsons Archive, homepage">
	<meta name="description" content="The Simpsons Archive Database">
	<meta name="author" content="Kimberly Branch, Timmy Chandy, William Posey, Matt Weingarten">
	<meta name="copyright" content="Copyright &copy 2017, All Rights Reserved">
    <style>
    <style style="text/css">
    .datagrid table { border-collapse: collapse; text-align: left; width: 100%; } 
    .datagrid {		
	font: Arial, Helvetica, sans-serif; 
	background: #fff; overflow: hidden; border: 10px solid #36752D; 
	-webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; 
     }
     .datagrid table td, .datagrid table th { padding: 3px 10px; }
     .datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #36752D), color-stop(1, #275420) );background:-moz-linear-gradient( center top, #36752D 5%, #275420 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#36752D', endColorstr='#275420');background-color:#36752D; color:#FFFFFF; font-size: 15px; font-weight: bold; border-left: 1px solid #36752D; } .datagrid table thead th:first-child { border: none; }.datagrid table tbody td { color: #275420; border-left: 1px solid #C6FFC2;font-size: 20px;font-weight: normal; }.datagrid table tbody .alt td { background: #DFFFDE; color: #275420; }.datagrid table tbody td:first-child { border-left: none; }.datagrid table tbody tr:last-child td { border-bottom: none; }.datagrid table tfoot td div { border-top: 1px solid #36752D;background: #DFFFDE;} .datagrid table tfoot td { padding: 0; font-size: 18px } .datagrid table tfoot td div{ padding: 2px; }.datagrid table tfoot td ul { margin: 0; padding:0; list-style: none; text-align: right; }.datagrid table tfoot  li { display: inline; }.datagrid table tfoot li a { text-decoration: none; display: inline-block;  padding: 2px 8px; margin: 1px;color: #FFFFFF;border: 10px solid #36752D;-webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #36752D), color-stop(1, #275420) );background:-moz-linear-gradient( center top, #36752D 5%, #275420 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#36752D', endColorstr='#275420');background-color:#36752D; }.datagrid table tfoot ul.active, .datagrid table tfoot ul a:hover { text-decoration: none;border-color: #275420; color: #FFFFFF; background: none; background-color:#36752D;}div.dhtmlx_window_active, div.dhx_modal_cover_dv { position: fixed !important; }
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
    function ajaxFunction(){
	var ajaxRequest;  // The variable that makes Ajax possible!	
	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	}catch (e){
	// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		}catch (e) {
			  try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			  }catch (e){
				 // Something went wrong
				 alert("Your browser broke!");
				 return false;
			  }
		 }
	}
	// Create a function that will receive data 
	// sent from the server and will update
	// div section in the same page.
	ajaxRequest.onreadystatechange = function(){
		if(ajaxRequest.readyState == 4){
			var ajaxDisplay = document.getElementById('resultDiv');
			ajaxDisplay.innerHTML = ajaxRequest.responseText;
		 }
	}
	// Now get the value from user and pass it to
	// server script.	
	ajaxRequest.open("GET", "SimpsonsgetTuples.php", true);
	ajaxRequest.send(null); 
     }
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
                    <li><a href="SimpsonsHomePage.php">Home</a></li>
                    <li><a href="SimpsonsRetrieval.php">Retrieval</a></li>
                    <li><a href="#">Explore</a></li> 
                    <li><a href="SimpsonsTeam.php">Team</a></li> 
                    <li class = "active"><a href="#">Inspiration</a></li> 
                </ul>
            </div>
        </div>
        </nav>
    </div>

    <div id ="summary">
			<table>
				<tr> 
					<td> <form name="myform" method="post">
						<p>The Simpsons has been on for nearly 30 years now, and for any successful long-running show, there comes a lot of data with it, whether it be in the form of episodes, locations, and/or characters. We've therefore created this website to allow users who are fans of The Simpsons to browse everything that has ever happened on the show. We hope you enjoy!</p>
						<div id = 'buttonDiv'>&nbsp;
						<input type= "button" class="btn btn-success" style = "display: block; margin: 0 auto; color:white" value="Calculate Tuples" onclick="ajaxFunction();"></input> <!--Button-->
						
						</form>
					</td>
				</tr>	
				
				
			</table>
	</div>
	
	<div id='resultDiv'></div>
</div>

</body>
</html>
