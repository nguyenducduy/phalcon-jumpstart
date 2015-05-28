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
    <link href="{{ static_url('min/index.php?g=cssCommon&rev=' ~ config.cssVersion) }}" rel="stylesheet">

  </head>

  <!-- Body -->
  <body class="gray-bg">
    <!-- Fake scrollbar (when open popups/modals) -->
    <div class="fake-scrollbar"></div>

    <!-- Off-canvas Navigation -->
    <div class="offcanvas-nav">
      <!-- Head (Fixed Part) -->
      {% include "layouts/header.volt" %}
      <!-- Body (Scroll Part) -->
      <div class="nav-body">
        <div class="overflow">
          <div class="inner">
            <!-- Navigation -->
            <nav class="nav-link">
              <div class="scroll-nav">
                <ul>
                  <li><a href="{{ url('2015/05/26/install-phalcon-jumpstart') }}">Install</a></li>
                  <li><a href="{{ url('2015/05/26/writing-hello-world-app') }}">HelloWorld</a></li>
                  <li><a href="{{ url('2015/05/26/directory-structure') }}">Directory</a></li>
                  <li><a href="{{ url('2015/05/26/modules-structure') }}">Modules</a></li>
                  <li><a href="{{ url('2015/05/26/models-structure') }}">Models</a></li>
                  <li><a href="{{ url('2015/05/26/views-structure') }}">Views</a></li>
                  <li><a href="{{ url('2015/05/26/routes') }}">Route</a></li>
                </ul>
                <ul>
                  <li><a href="{{ url('2015/05/26/multi-language-structure') }}">Multilingual</a></li>
                  <li><a href="{{ url('2015/05/26/css-js-minifier') }}">CSS/JS Minifier</a></li>
                  <li><a href="{{ url('2015/05/26/php-cli') }}">PHP-CLI</a></li>
                  <li><a href="{{ url('2015/05/26/db-migration') }}">DB-Migration</a></li>
                  <li><a href="{{ url('2015/05/26/database-logger') }}">Logger</a></li>
                  <li><a href="{{ url('2015/05/26/code-generator') }}">Code-Generator</a></li>
                  <li><a href="{{ url('2015/05/26/permission-acl') }}">ACL</a></li>
                </ul>
              </div>
              <ul class="pages">
                <li><a href="/">Home</a></li>
                <li class="active"><a href="/">Blog</a></li>
                <li><a href="{{ url('?type=3&orderby=datecreated&ordertype=desc') }}">Courses</a></li>
                <!-- <li><a href="press.html">Portfolio</a></li> -->
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div><!-- Off-canvas Navigation End -->

    <!-- Navbar -->
    <header class="navbar navbar-fixed-top">
      <div class="container">
        <!-- Nav Toggle -->
        <div class="nav-toggle waves-effect waves-light waves-circle" data-offcanvas="open"><i class="flaticon-menu55"></i></div>
        <!-- Logo -->
        <a href="/" class="logo">
          <img src="{{ static_url('images/logo.png') }}" alt="PhalconPHP Jumpstart">
          PhalconPHP Jumpstart
        </a>
        <!-- Toolbar -->
        <div class="toolbar">
          <a href="javascript:void(0)" class="btn btn-flat btn-light icon-left waves-effect waves-light"></a>
          <!-- Social Buttons -->
          <div class="social-buttons text-right">
            <iframe src="https://ghbtns.com/github-btn.html?user=nguyenducduy&repo=phalcon-jumpstart&type=star&count=true" frameborder="0" scrolling="0" width="170px" height="20px"></iframe>
          </div>
        </div>
      </div>
    </header><!-- Navbar End -->

    <!-- Page Heading -->
    <!-- <div class="page-heading text-right">
      <div class="container">
        <form class="search-field form-control" method="post" action="{{ url('search') }}">
          <button type="submit" class="search-btn"><i class="flaticon-search100"></i></button>
          <input type="text" id="search-input" value="{% if formData['conditions']['keyword'] is defined %}{{ formData['conditions']['keyword'] }}{% endif %}">
          <label for="search-input">Search</label>
        </form>
        <h2>Blog</h2>
      </div>
    </div> -->

    {% block content %}{% endblock %}

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-55911554-1', 'auto');
      ga('send', 'pageview');

    </script>

    {% include "layouts/footer.volt" %}

    <script type="text/javascript" src="{{ static_url('js/common/jquery-2.1.3.min.js') }}"></script>
    <script type="text/javascript" src="{{ static_url('js/common/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ static_url('js/common/highlight.pack.js') }}"></script>
    <!-- Javascript (jQuery) Libraries and Plugins -->
    <script type="text/javascript" src="{{ static_url('min/index.php?g=jsCommon&rev=' ~ config.jsVersion) }}"></script>
    <script type="text/javascript">
      var root_url = "{{ url.getBaseUri() }}";
    </script>
  </body><!-- Body End-->
</html>