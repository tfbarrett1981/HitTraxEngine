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
  <body  <?php body_class(isset($class) ? $class : ''); ?>>
    <header>
        <div class="container banner">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <img id="logo" src="<?php echo get_bloginfo("template_url") ;?>/images/logo.png" alt="">
                    <p>&nbsp;</p>
                    </div>
            </div>
        </div>
        <div class="container">
            <nav class="row row-centered">
                <ul>
                    <li class="hidden-xs   col-sm-2"><img src="<?php echo get_bloginfo("template_url") ;?>/images/divider-home.png" class="homeDivider" alt=""></li>
                    <li class="col-xs-6 col-sm-2"><div ><img src="<?php echo get_bloginfo("template_url") ;?>/images/icon-players-hover.png" alt="">PLAYERS</div></li>
                    <li class="col-xs-6 col-sm-2"><div ><img src="<?php echo get_bloginfo("template_url") ;?>/images/icon-coaches-hover.png" alt="">COACHES</div></li>
                    <li class="col-xs-6 col-sm-2"><div ><img src="<?php echo get_bloginfo("template_url") ;?>/images/icon-owners-hover.png" alt="">FACILITY OWNERS</div></li>
                    <li class="col-xs-6 col-sm-2"><div ><img src="<?php echo get_bloginfo("template_url") ;?>/images/icon-recruiters-hover.png" alt="">RECRUITERS</div></li>
                    <li class="hidden-xs   col-sm-2"><img src="<?php echo get_bloginfo("template_url") ;?>/images/divider-home.png"  class="homeDivider last"  alt=""></li>
                </ul>
            </nav>
        </div>
        <br /><br />        
        <div class="container">
            <div class="row row-centered">
                <div class="col-xs-10 col-sm-4 col-centered">
                    <div id="login_failure" class="alert alert-danger" style='<?php if(!$_GET["login"] == "failed"){ ?>display:none; <?php } ?>text-align:center;'><strong>Incorrect username/password.</strong></div>
	                <form action="http://engine.hittraxstatscenter.com/wp-login.php" method="post">
                    <div class="form-group">
                        <input  type="text" name="log" id="log" placeholder="Username" class="form-control input-lg">
                    </div><br />
                    <div class="form-group">
                        <input type="password" name="pwd" id="pwd" placeholder="Password" class="form-control input-lg">
                    </div><br />
                    <div class="form-group text-center">
                    <input type="submit" value="SUBMIT" class="input-lg">
                    <br>
                    <a href="#">FORGOT YOUR PASSWORD?</a>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </header>  