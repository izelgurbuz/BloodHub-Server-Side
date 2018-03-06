<?php $path_parts = pathinfo(basename($_SERVER['PHP_SELF']));?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

    <head>
        <meta charset="utf-8">
        <title> <?php if(isset($GLOBALS['post_tit'])) echo $GLOBALS['post_tit']; else echo ucfirst($path_parts['filename']);?> - the bloodhub.</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="description" content="the bld.">
        <meta name="author" content="xenioushk">
        <link rel="shortcut icon" href="http://cs491-2.mustafaculban.net/images/favicon.png" />

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <!-- The styles -->
        <link rel="stylesheet" href="http://cs491-2.mustafaculban.net/css/bootstrap.min.css" />
        <link href="http://cs491-2.mustafaculban.net/css/font-awesome.min.css" rel="stylesheet" type="text/css" >
        <link href="http://cs491-2.mustafaculban.net/css/animate.css" rel="stylesheet" type="text/css" >
        <link href="http://cs491-2.mustafaculban.net/css/owl.carousel.css" rel="stylesheet" type="text/css" >
        <link href="http://cs491-2.mustafaculban.net/css/venobox.css" rel="stylesheet" type="text/css" >
        <link rel="stylesheet" href="http://cs491-2.mustafaculban.net/css/styles.css" />

        <script src="http://cs491-2.mustafaculban.net/js/pdf/pdf.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src="http://cs491-2.mustafaculban.net/js/bootstrap.min.js"></script>
        <script src="http://cs491-2.mustafaculban.net/js/wow.min.js"></script>
        <script src="http://cs491-2.mustafaculban.net/js/jquery.backTop.min.js "></script>
        <script src="http://cs491-2.mustafaculban.net/js/waypoints.min.js"></script>
        <script src="http://cs491-2.mustafaculban.net/js/waypoints-sticky.min.js"></script>
        <script src="http://cs491-2.mustafaculban.net/js/owl.carousel.min.js"></script>
        <script src="http://cs491-2.mustafaculban.net/js/jquery.stellar.min.js"></script>
        <script src="http://cs491-2.mustafaculban.net/js/jquery.counterup.min.js"></script>
        <script src="http://cs491-2.mustafaculban.net/js/venobox.min.js"></script>
        <script src="http://cs491-2.mustafaculban.net/js/custom-scripts.js"></script>
        <script type="text/javascript" src="js/validation.min.js"></script>
        <script type="text/javascript" src="js/sendFormAPI.js"></script>

        <?php /*<link rel="manifest" href="push/manifest.json">
        <script type="text/javascript" src="https://mustafa.bildirim.net/o434790286.js"></script>
        <script>
            var webPush = new webPush({}) || [];
            webPush.init();
        </script>*/
        require 'push/oneSignal.php';
        $oneSignal = new oneSignal();
        ?>
        <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async='async'></script>
        <script>
            var OneSignal = window.OneSignal || [];
            OneSignal.push(["init", {
                appId: '<?=$oneSignal->__construct();?>',
                autoRegister: false, /* Set to true to automatically prompt visitors */
                subdomainName: 'bloodhub', // Uygulamayı oluştururken aldığınız subDomain

                notifyButton:
                {
                    enable: true, /* Required to use the notify button */
                    size: 'large', /* One of 'small', 'medium', or 'large' */
                    theme: 'inverse', /* One of 'default' (red-white) or 'inverse" (white-red) */
                    position: 'bottom-left', /* Either 'bottom-left' or 'bottom-right' */
                    offset: {
                        bottom: '0px',
                        left: '0px', /* Only applied if bottom-left */
                        right: '0px' /* Only applied if bottom-right */
                    },
                    prenotify: true, /* Show an icon with 1 unread message for first-time site visitors */
                    showCredit: false, /* Hide the OneSignal logo */
                    text: {
                        'tip.state.unsubscribed': 'Abone ol',
                        'tip.state.subscribed': "Aramıza Hoş Geldin :)",
                        'tip.state.blocked': "Bildirimlerin Engellendi.",
                        'message.prenotify': 'Bildirimlere Abone Olmak İçin Tıklayın.',
                        'message.action.subscribed': "Aramıza Hoş Geldin :)",
                        'message.action.resubscribed': "Abone oldun. Hoşgeldin :)",
                        'message.action.unsubscribed': "Abonelikten Ayrıldın.",
                        'dialog.main.title': 'OneSignal Api',
                        'dialog.main.button.subscribe': 'Abone Ol',
                        'dialog.main.button.unsubscribe': 'Abonelikten Ayrıl',
                        'dialog.blocked.title': 'Engelli Bildirimler.',
                        'dialog.blocked.message': "Follow these instructions to allow notifications:"
                    }
                }

            }]);
        </script>
        </head>
        <body> 

        <div id="preloader">
            <span class="margin-bottom"><img src="http://cs491-2.mustafaculban.net/images/loader.gif" alt="" /></span>
        </div>

        <!--  HEADER -->

        <header class="main-header clearfix" data-sticky_header="1">

            <div class="top-bar clearfix">

                <div class="container">

                    <div class="row">

                        <div class="col-md-8 col-sm-12">

                            <p>Welcome to blood donation center.</p>

                        </div>

                        <div class="col-md-4col-sm-12">
                            <div class="top-bar-social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-google-plus"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="#"><i class="fa fa-youtube"></i></a>
                            </div>   
                        </div> 

                    </div>

                </div> <!--  end .container -->

            </div> <!--  end .top-bar  -->

            <section class="header-wrapper navgiation-wrapper">

                <div class="navbar navbar-default">         
                    <div class="container">

                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="logo" href="/"><img alt="" src="http://cs491-2.mustafaculban.net/images/logo_new.png"></a>
                        </div>

                        <div class="navbar-collapse collapse">
                            <ul class="nav navbar-nav navbar-right">
                                <li class="drop">
                                    <a href="/" title="Home Layout 01">Home</a>
                                </li>
                                <li><a href="http://cs491-2.mustafaculban.net/about-us" title="About Us">About Us</a></li>



                                <li>
                                    <a href="#">Campaign</a>
                                    <ul class="drop-down">
                                        <li><a href="http://cs491-2.mustafaculban.net/events">All Campaigns</a></li>
                                        <!--<li><a href="event-single.html">Single Campaign</a></li>-->
                                    </ul>
                                </li>

                                <li class="drop"><a href="#">Pages</a>
                                    <ul class="drop-down">


                                        <li><a href="http://cs491-2.mustafaculban.net/reports" title="Reports">Reports</a></li>
                                        <li class="drop"><a href="http://cs491-2.mustafaculban.net/gallery">Gallery</a>
                                            <ul class="drop-down level3">
                                                    <!--<li><a href="gallery-1.html">Layout 01</a></li> -->
                                                    <!--<li><a href="gallery-2.html">Layout 02</a></li> -->
                                            </ul>
                                        </li>
                                        <li><a href="http://cs491-2.mustafaculban.net/404" title="404 Page">404 Page</a></li> 
                                        <li class="drop"><a href="#">Level 3</a>
                                            <ul class="drop-down level3">
                                                <li><a href="#">Level 3.1</a></li>
                                                <li><a href="#">Level 3.2</a></li>
                                                <li><a href="#">Level 3.3</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>

                                <li>
                                    <a href="http://cs491-2.mustafaculban.net/blog">Blog</a>
                                    <!--<ul class="drop-down">
                                        <li><a href="blog.html">All Posts</a></li> 
                                        <li><a href="single.html">Single Page</a></li>
                                    </ul>-->
                                </li>

                                <li><a href="http://cs491-2.mustafaculban.net/contact">Contact</a></li>

                                <li><a target="_blank" href="http://cs491-2.mustafaculban.net/api/documentation/index.html">DOCUMENTATION</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </section>

        </header> <!-- end main-header  -->