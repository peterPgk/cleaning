<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/app.css">
    <title>@yield('title')</title>
    <meta property="og:description" content="The best comparison cleaning website."/>
    <meta property="og:url" content="http://compare.ofertiko.com"/>
    <meta property="og:title" content="Compare website"/>
    <meta property="og:image" content="http://compare.ofertiko.com/img/samplelogo.png"/>

    <!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '121268051722355'); // Insert your pixel ID here.
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=121268051722355&ev=PageView&noscript=1"
/></noscript>
<!-- DO NOT MODIFY -->
<!-- End Facebook Pixel Code -->
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.8";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<header class="container-fluid ">
    <h2 class="brand pull-left vcenter">Logo</h2>
    <h2 class="brand pull-right vcenter">Help</h2>
</header>

    <style>
        .booking-master .form-control{
            padding: 5px;
        }
        .booking-master .comparestep6 .btn.btn-primary{
            margin-top:50px;
            padding:-15px;
        }
    </style>

<div class="container booking-master" id="general_app" v-cloak>
    @yield('content')
</div>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-58907336-2', 'auto');
  ga('send', 'pageview');
</script>

<script>
    restoreData = <?= isset($restore_data) ? json_encode($restore_data) : json_encode([]) ?>
</script>
<script src="https://js.stripe.com/v2/"></script>
<script src="/js/app.js"></script>
@yield('js')
</body>
</html>