    <!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>
    <script src="https://use.typekit.net/fmq0kbm.js"></script>
    <script>
        try{Typekit.load({async:true}); }catch(e){} 
        </script>
    <!-- Bootstrap -->
    <link href="<?php echo get_bloginfo("template_url") ;?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo get_bloginfo("template_url") ;?>/css/style.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body  <?php body_class(isset($class) ? $class : 'main'); ?>>
    <header>
        <div class="container banner">
            <div class="row">
                <div class="col-md-5 col-lg-6"><img id="logo" src="<?php echo get_bloginfo("template_url") ;?>/images/logo.png" alt=""></div>
                <div class="col-md-5 col-lg-6">
                    <img src="<?php echo get_bloginfo("template_url") ;?>/images/profile.png" class="pull-right" alt="">
                    <div class="pull-right topWrapper">
                        <h2>Firstname Lastname</h2>
                        <ul class="topnav">
                            <li><a  class="tk-proxima-nova" href="#">Hittrax Recruiter</a></li>
                            <li><a class="tk-proxima-nova" href="#">Organization Name</a></li>
                            <li ><a class="tk-proxima-nova" href="#">Logout</a></li>
                        </ul>
                    </div>
                    

                </div>
            </div>
        </div>
       