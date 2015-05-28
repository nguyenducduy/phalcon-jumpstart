<html itemscope itemtype="http://schema.org/Product">
<head>
    <meta charset="utf-8">

    <!-- Site Title -->
    {{ getTitle() }}

    <!-- SEO Meta Tags -->
    <meta name="description" content="Powerful Phalcon Developer Tool with CRUD code generator using to speed up project develop process" />
    <meta name="keywords" content="phalcon, phalconphp, phalcon jumpstart, jumpstart with phalconphp, phalcon developer tool, phalcon code generator" />
    <meta name="author" content="Nguyễn Đức Duy" />

    <!-- Mobile Specific Meta Tag -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="Phalcon Jumpstart">
    <meta itemprop="description" content="Powerful Phalcon Developer Tool with CRUD code generator using to speed up project develop process">
    <meta itemprop="image" content="http://phalconjumpstart.com/images/logo-brand.png">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@phalconjumpstart.com">
    <meta name="twitter:creator" content="@nguyenducduyit">
    <meta name="twitter:title" content="Phalcon Jumpstart">
    <meta name="twitter:description" content="Powerful Phalcon Developer Tool with CRUD code generator using to speed up project develop process.">
    <meta name="twitter:image" content="http://phalconjumpstart.com/images/logo-brand.png">

    <!-- Open Graph data -->
    <meta property="og:title" content="Phalcon Jumpstart" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="http://phalconjumpstart.com/" />
    <meta property="og:image" content="http://phalconjumpstart.com/images/logo-brand.png" />
    <meta property="og:description" content="Powerful Phalcon Developer Tool with CRUD code generator using to speed up project develop process" />
    <meta property="og:site_name" content="Phalcon Jumpstart" />

    <!-- Favicon Icon -->
    <link rel="shortcut icon" href="{{ static_url('images/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ static_url('images/favicon.ico') }}" type="image/x-icon">

    <!-- Theme Stylesheet -->
    <link href="{{ static_url('min/index.php?g=cssProfile&rev=' ~ config.cssVersion) }}" rel="stylesheet">
    <script type="text/javascript" src="{{ static_url('js/profile/jquery-1.11.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ static_url('js/profile/jquery-migrate-1.2.1.min.js') }}"></script>

  </head>

  <!-- Body -->
  <body class="gray-bg">

  {% block content %}{% endblock %}

    // <script>
    //   (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    //   (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    //   m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    //   })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    //   ga('create', 'UA-55911554-1', 'auto');
    //   ga('send', 'pageview');

    // </script>

    <script type="text/javascript" src="{{ static_url('js/profile/bootstrap.min.js') }}"></script>
    <!-- Javascript (jQuery) Libraries and Plugins -->
    <script type="text/javascript" src="{{ static_url('min/index.php?g=jsProfile&rev=' ~ config.jsVersion) }}"></script>
    <script type="text/javascript">
      var root_url = "{{ url.getBaseUri() }}";
    </script>
  </body><!-- Body End-->
</html>