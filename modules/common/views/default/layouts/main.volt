<html>
<head>
    <meta charset="utf-8">

    <!-- Site Title -->
    {{ getTitle() }}

    <!-- SEO Meta Tags -->
    <meta name="description" content="Responsive HTML5 Theme in Material Design Style" />
    <meta name="keywords" content="responsive html5 theme, ios, android, material design, landing, application, mobile, blog, portfolio, bootstrap 3, css, jquery, flat, modern" />
    <meta name="author" content="8Guild" />

    <!-- Mobile Specific Meta Tag -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

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
                  <li><a href="#">Install</a></li>
                  <li><a href="#">HelloWorld</a></li>
                  <li><a href="#">Directory</a></li>
                  <li><a href="#">Modules</a></li>
                  <li><a href="#">Models</a></li>
                  <li><a href="#">Views</a></li>
                  <li><a href="#">Route</a></li>
                  <li><a href="#">ACL</a></li>
                </ul>
                <ul>
                  <li><a href="#">Multilingual</a></li>
                  <li><a href="#">CSS/JS Minifier</a></li>
                  <li><a href="#">PHP-CLI</a></li>
                  <li><a href="#">DB-Migration</a></li>
                  <li><a href="#">Logger</a></li>
                  <li><a href="#">Code-Generator</a></li>
                  <li><a href="#">Pushstate</a></li>
                  <li><a href="#">Data-bindings</a></li>
                </ul>
              </div>
              <ul class="pages">
                <li><a href="/">Home</a></li>
                <li class="active"><a href="blog.html">Blog</a></li>
                <li><a href="blog-single.html">eBooks</a></li>
                <li><a href="{{ url('?type=3&orderby=datecreated&ordertype=desc') }}">Courses</a></li>
                <li><a href="press.html">Portfolio</a></li>
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
            <a href="#" class="sb-twitter"><i class="bi-twitter"></i></a>
            <a href="#" class="sb-google-plus"><i class="bi-gplus"></i></a>
            <a href="#" class="sb-facebook"><i class="bi-facebook"></i></a>
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

    {% include "layouts/footer.volt" %}

    <script type="text/javascript" src="{{ static_url('js/common/jquery-2.1.3.min.js') }}"></script>
    <script type="text/javascript" src="{{ static_url('js/common/bootstrap.min.js') }}"></script>
    <!-- Javascript (jQuery) Libraries and Plugins -->
    <script type="text/javascript" src="{{ static_url('min/index.php?g=jsCommon&rev=' ~ config.jsVersion) }}"></script>
    <script type="text/javascript">
      var root_url = "{{ url.getBaseUri() }}";
    </script>
  </body><!-- Body End-->
</html>