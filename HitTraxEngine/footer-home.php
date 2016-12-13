


    <footer class="large">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <img src="<?php echo get_bloginfo("template_url") ;?>/images/logo-footer.png" alt="">
                    <div class="footerLeft">
                        <h4>123.456.7890</h4>
                        <h4><a href="mailto:info@hittraxbaseball.com">info<span>@</span>hittraxbaseball.com</a></h4>
                        <br />
                        <input type="submit" class="button" value="login now">
                    </div>
                </div>
                <div class="footerNav col-md-2">
                    <ul>
                        <li><a href="">For Players</a></li>
                        <li><a href="">For Coaches</a></li>
                        <li><a href="">For Your Facility</a></li>
                        <li><a href="">What's New</a>&nbsp;&nbsp;&nbsp;</li>
                        <li><a href="">Blog</a></li>
                        <li><a href="">Find a Facility</a></li>
                    </ul>
                </div>
                <div class="socialNav col-md-5 pull-right">
                    <ul class="clearfix">
                        <li><a href=""><img src="<?php echo get_bloginfo("template_url") ;?>/images/social-facebook.png" alt=""></a></li>
                        <li><a href=""><img src="<?php echo get_bloginfo("template_url") ;?>/images/social-twitter.png" alt=""></a></li>
                        <li><a href=""><img src="<?php echo get_bloginfo("template_url") ;?>/images/social-youtube.png" alt=""></a></li>
                        <li><a href=""><img src="<?php echo get_bloginfo("template_url") ;?>/images/social-instagram.png" alt=""></a></li>
                        <li><a href=""><img src="<?php echo get_bloginfo("template_url") ;?>/images/social-snapchat.png" alt=""></a></li>
                    </ul>
                    <h5>STAY CONNECTED WITH HITTRAX</h5>
                    <p class="tk-prima-nova">Join the HitTrax mailing list for exclusive Donec 
lacinia dui, a porttitor lectus condimentum laoreet.
</p> 
<input type="email">
<input type="submit" class="button" value="SIGN ME UP">
                </div>
            </div>
        </div>
    </footer>
    <footer class="footerBar">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-xs-12 privacyTerms">
                    <a href="">PRIVACY POLICY </a>   &bull; <a href="">TERMS OF USE</a>
                </div>
                <div class="col-md-6 col-xs-12 copyright">&copy; 2016 INMOTION SYSTEMS, LLC. ALL RIGHTS RESERVED.</div>
            </div>
        </div>
    </footer>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <?php wp_footer(); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo get_bloginfo("template_url") ;?>/js/bootstrap.min.js"></script>
    <script>
        jQuery( document ).ready(function($) {
            
            $( ".tab" ).each(function( index ) {
                    $("." + $(this).data("tab")).hide();
                });
            $(".tab").click(function(e){
                $selectedTab = $(this);
                $( ".tab" ).each(function( index ) {
                    $("." + $(this).data("tab")).hide();
                    $(this).find("img").attr("src", "images/tab-arrow.png");
                });

                $selectedTab.find("img").attr("src", "images/tab-x.png");
                var tabPanel = "." + $selectedTab.data("tab");
                $(tabPanel).show();

            });

            $(".search select").change(function(){
                var changedSelect = $(this);
                var label = $(this).siblings("label");
                $(".searchItems").find("." + changedSelect.attr("id")).remove();
                if($(this).val() != ""){
                $(".searchItems").append("<li data-parent="+ changedSelect.attr("id") +" class='"+ changedSelect.attr("id") +" tk-proxima-nova'><span class='glyphicon glyphicon-remove-circle'></span>"+ label.text() +": "+ changedSelect.val() +"</li>");
                }
                
            });

            $(".search input[type='text']").focusout(function(){
                var changedSelect = $(this);
                var label = $(this).siblings("label");
                $(".searchItems").find("." + changedSelect.attr("id")).remove().delay("1000");
                if($(this).val() != ""){
                $(".searchItems").delay(100).queue(function(next){
                    $(this).append("<li data-parent="+ changedSelect.attr("id") +" class='"+ changedSelect.attr("id") +" tk-proxima-nova'><span class='glyphicon glyphicon-remove-circle'></span>"+ label.text() +": "+ changedSelect.val() +"</li>");
                    next();
                    });

                }
                
            });

            $(".results table tr td:first-child").click(function(e){
                var selectedRow = $(this);
                $(".results table tr").removeClass("selected");
                selectedRow.parent().addClass("selected");
            });

            $("body").on("click", ".glyphicon-remove-circle", function(){
                var parentFormElement = $(this).parent().data("parent");
                $(this).parent().remove();
                $("#"+ parentFormElement).val('');
            });

        });
    </script>
  </body>
</html>