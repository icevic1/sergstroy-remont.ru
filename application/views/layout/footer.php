    <!-- Load Facebook SDK for JavaScript -->
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId      : '961073814038310',
                xfbml      : true,
                version    : 'v2.8'
            });
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/ru_RU/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        window.fbShare = function() {
            FB.ui({
                    method: 'share',
                    href: '<?php echo site_url();?>',
                    title: 'Студия Ремонта',  // The same than name in feed method
                    picture: '<?php echo base_url('/public/img/logo/logo.png');?>',
                    caption: '+7 (926) 923-19-45',
                    description: 'SERG-STROY.RU это студия качественного и быстрого ремонта за разумную цену в Москве и Московской Области!',
                }, function (response) {
            });
        }
        //TODO: something not work
        /*window.postToWallUsingFBUi = function()
        {
            FB.ui({
                method: 'stream.publish',
                message: "Posted using FB.ui and picture.",
                display: 'iframe',
                caption: "+7 (926) 923-19-45",
                name: "Студия Ремонта",
                picture: ' //echo base_url('/public/img/logo/logo.png');',
                link: " //echo site_url();",  // Go here if user click the picture
                description: "SERG-STROY.RU это студия качественного и быстрого ремонта за разумную цену в Москве и Московской Области!",
                actions: [{ name: 'action_links text!', link: 'http://www.permadi.com' }],
            }, function (response) {
            });
        }*/

        Share = {
            vkontakte: function(purl, ptitle, pimg, text) {
                url  = 'http://vkontakte.ru/share.php?';
                url += 'url='          + encodeURIComponent(purl);
                url += '&title='       + encodeURIComponent(ptitle);
                url += '&description=' + encodeURIComponent(text);
                url += '&image='       + encodeURIComponent(pimg);
                url += '&noparse=true';
                Share.popup(url);
            },
            odnoklassniki: function(purl, text) {
                url  = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1';
                url += '&st.comments=' + encodeURIComponent(text);
                url += '&st._surl='    + encodeURIComponent(purl);
                Share.popup(url);
                return false;
            },
            facebook: function(purl, ptitle, pimg, text) {
                url  = 'http://www.facebook.com/sharer.php?s=100';
                url += '&title='     + encodeURIComponent(ptitle);
                url += '&summary='   + encodeURIComponent(text);
                url += '&url='       + encodeURIComponent(purl);
                url += '&images=' + encodeURIComponent(pimg);
                Share.popup(url);

            },
            twitter: function(purl, ptitle) {
                url  = 'http://twitter.com/share?';
                url += 'text='      + encodeURIComponent(ptitle);
                url += '&url='      + encodeURIComponent(purl);
                url += '&counturl=' + encodeURIComponent(purl);
                Share.popup(url);
            },
            mailru: function(purl, ptitle, pimg, text) {
                url  = 'http://connect.mail.ru/share?';
                url += 'url='          + encodeURIComponent(purl);
                url += '&title='       + encodeURIComponent(ptitle);
                url += '&description=' + encodeURIComponent(text);
                url += '&imageurl='    + encodeURIComponent(pimg);
                Share.popup(url)
            },
            google: function(purl, text) {
//                url  = 'https://m.google.com/app/plus/x/?v=compose';
                url  = 'https://plus.google.com/share?';
//                url += '&content=' + encodeURIComponent(text);
                url += 'url='    + encodeURIComponent(purl);
                Share.popup(url);
                return false;
            },
            popup: function(url) {
                window.open(url,'','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,width=626,height=436');
            }
        };
    </script>

    <link href="<?php echo base_url('public/css/magnific-popup.css');?>" rel="stylesheet" type="text/css">
<!--    <link href="http://isotope.metafizzy.co/css/isotope-docs.css" rel="stylesheet" type="text/css">-->
    <script src="<?php echo base_url('assets/js/moment/moment.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.validate.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/dataTables.bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap-tabcollapse.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap-datetimepicker.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/underscore-min.js'); ?>"></script>
    <!-- script src="<?php //echo base_url('assets/bootstrap/js/bootstrap-checkbox.min.js'); ?>" defer></script> create nice checkbox  -->
    <!-- script src="<?php //echo base_url('assets/js/jquery-ui-1.10.0.custom.min.js'); ?>" type="text/javascript"></script-->
    <script src="<?php echo base_url('public/js/jquery.cookie.js'); ?>"></script>
    <script src="<?php echo base_url('public/js/jquery.inputmask.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootbox.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/responsive-calendar.min.js'); ?>"></script>
    <script src="<?php echo base_url('public/js/jquery.magnific-popup.js');?>"></script>
    <script src="<?php echo base_url('assets/js/isotope.pkgd.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/scripts.js'); ?>"></script>

    <!--<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            console.log(js);
            js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.8";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        window.twttr = (function (d, s, id) {
         var t, js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) return;
         js = d.createElement(s);
         js.id = id;
         js.src = "//platform.twitter.com/widgets.js";
         fjs.parentNode.insertBefore(js, fjs);
         return window.twttr || (t = {
         _e: [],
         ready: function (f) {
         t._e.push(f)
         }
         });
         }(document, "script", "twitter-wjs"));

        window.twttr.events.bind('click', function(event) {
         console.log('clicked');
         alert('bro');
         });
    </script>-->
  </body>
</html>