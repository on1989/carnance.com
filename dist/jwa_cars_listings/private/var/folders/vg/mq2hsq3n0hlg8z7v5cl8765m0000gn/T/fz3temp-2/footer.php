
<?
$office_items = get_field('office_items', 'option');
$office_href_google_map = get_sub_field('office_href_google_map', 'option');
$office_let = get_field('office_let', 'option');
$office_lng = get_field('office_lng', 'option');
$office_address = get_field('office_address', 'option');
$office_phone = get_field('office_phone', 'option');
$office_fax = get_field('office_fax', 'option');
$office_phone_two = get_field('office_phone_two', 'option');
$office_email = get_field('office_email', 'option');
$office_title = get_field('office_title', 'option');
$footer_form = get_field('footer_form', 'option');
$location_title = get_field('location_title', 'option');
$hours_title = get_field('hours_title', 'option');
$hours_day = get_field('hours_day', 'option');
$hours = get_field('hours', 'option');
$button_phone = get_field('button_phone', 'option');
$copyright = get_field('copyright', 'option');
$installation_title = get_field('installation_title', 'option');
?>
<footer>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="footer-form">
                    <?php echo do_shortcode($footer_form); ?>
                </div>
                <div class="location">
                    <h3><?php echo $location_title; ?></h3>
                    <ul class="office">
                        <?php foreach ($office_items as $item): ?>
                        <li>
                            <a href="<?php echo $item['office_href_google_map']; ?>"
                                data-lat="<?php echo $item['office_let']; ?>"
                                data-lng="<?php echo $item['office_lng']; ?>"
                                data-address="<?php echo $item['office_address']; ?>"
                                data-phone="<?php echo $item['office_phone']; ?>"
                                data-fax="<?php echo $item['office_fax']; ?>"
                                data-phone-two="<?php echo $item['office_phone_two']; ?>"
                                data-email="<?php echo $item['office_email']; ?>"><?php echo $item['office_title']; ?></a>
                        </li>
                        <?php endforeach;?>
                    </ul>
                    <div class="hours">
                        <h6><?php echo $hours_title; ?></h6>
                        <?php foreach ($hours_day as $item): ?>
                        <p><?php echo $item['hours']; ?></p>
                        <?php endforeach;?>
                    </div>
                </div>
                <div id="map"></div>
            </div>
        </div>
    </div>
    <div class="bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a class="homestars" href="#">
                        <img src="<?php bloginfo('template_url')?>/assets/img/homestars.svg" alt="homestars"></a>
                    <?php wp_nav_menu([
                          'theme_location' => 'footer',
                          'menu' => 'footer',
                          'container' => '',
                          'container_class' => '',
                          'container_id' => '',
                          'menu_class' => 'menu',
                          'menu_id' => 'footer-menu',
                          'echo' => true,
                          'fallback_cb' => 'wp_page_menu',
                          'before' => '',
                          'after' => '',
                          'link_before' => '',
                          'link_after' => '',
                          'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                          'depth' => 0,
                          'walker' => '',
                      ]);
                      ?>
                    <a class="button green icon-phone"
                        href="tel:<?php echo $button_phone; ?>"><?php echo $button_phone; ?></a>

                </div>
            </div>
        </div>
    </div>
    <div class="location-block">
        <div class="container">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <h4><?php echo $installation_title;?></h4>
               <?php wp_nav_menu([
				  'theme_location' => 'Location-Menu',
				  'menu' => 'Location-Menu',
				  'container' => '',
				  'container_class' => '',
				  'container_id' => ' ',
				  'menu_class' => 'location-menu',
				  'menu_id' => '',
				  'echo' => true,
				  'fallback_cb' => 'wp_page_menu',
				  'before' => '',
				  'after' => '',
				  'link_before' => '',
				  'link_after' => '',
				  'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				  'depth' => 0,
				  'walker' => '',
			  ]);
			  ?>
			<p class="copyright"><?php echo $copyright; ?></p>
           <?php wp_nav_menu([
				  'menu' => 'terms-menu',
				  'container' => '',
				  'container_class' => '',
				  'container_id' => '',
				  'menu_class' => 'terms-menu',
				  'menu_id' => 'terms-menu',
				  'echo' => true,
				  'fallback_cb' => 'wp_page_menu',
				  'before' => '',
				  'after' => '',
				  'link_before' => '',
				  'link_after' => '',
				  'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				  'depth' => 0,
				  'walker' => '',
			  ]);
             ?>
            </div>
          </div>
        </div>
      </div>
</footer>
<div class="modal fade coupon-modal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form class="form-coupon" method="POST">
                <h3>Coupon </h3>
                <div class="input">
                    <input type="text" placeholder="Name">
                </div>
                <div class="input">
                    <input type="email" placeholder="Email">
                </div>
                <div class="input">
                    <input type="tel" placeholder="Phone">
                </div>
                <div class="input">
                    <input class="button orange" type="submit" value="REQUEST NOW">
                </div>
            </form>
        </div>
    </div>
</div>
<script type='text/javascript' data-cfasync='false'>window.purechatApi = { l: [], t: [], on: function () { this.l.push(arguments); } }; (function () { var done = false; var script = document.createElement('script'); script.async = true; script.type = 'text/javascript'; script.src = 'https://app.purechat.com/VisitorWidget/WidgetScript'; document.getElementsByTagName('HEAD').item(0).appendChild(script); script.onreadystatechange = script.onload = function (e) { if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) { var w = new PCWidget({c: 'f75b58f6-bc25-4f46-a8ce-bba020282efa', f: true }); done = true; } }; })();</script>
<?php wp_footer()?>
	</body>
</html>
<!--
<script src="https://www.google.com/recaptcha/api.js?render=6LfV478UAAAAAK6QqyNuR_G1gkVmnIyDfSXBrjWE"></script>
<script>
grecaptcha.ready(function() {
    grecaptcha.execute('6LfV478UAAAAAK6QqyNuR_G1gkVmnIyDfSXBrjWE', {action: 'homepage'}).then(function(token) {
    });
});
</script>
-->
