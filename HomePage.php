<html>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<head>
    <title>The Simpsons Archive</title>
	<meta name="keywords" content="The Simpsons Archive, homepage">
	<meta name="description" content="The Simpsons Archive Database">
	<meta name="author" content="Kimberly Branch, Timmy Chandy, William Posey, Matt Weingarten">
	<meta name="copyright" content="Copyright &copy 2017, All Rights Reserved">

    <style type="text/css">

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

    .content {
        height: 100%;
        width: 100%;
    }

    body {
        background-color: white;
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
    </style>

    <script>
    // this sets the class to "active" of the currently active link in the navbar
    $(function() {
        $(".nav li").on("click", function() {
            $(".nav li").removeClass("active");
            $(this).addClass("active");
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
                        <img class="banner-img" src="images\bannerFamily.png" alt="Simpsons Image"/>
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
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Retrieval<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Characters</a></li>
                            <li><a href="#">Episodes</a></li>
                            <li><a href="#">Locations</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Explore</a></li> 
                    <li><a href="#">Team</a></li> 
                    <li><a href="#">Inspiration</a></li> 
                </ul>
            </div>
        </div>
        </nav>
    </div>

    <div class="content">
        
    </div>
</div>

</body>
</html>