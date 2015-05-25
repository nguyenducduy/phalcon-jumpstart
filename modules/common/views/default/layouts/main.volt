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
      <div class="nav-head">
        <div class="top-bar">
          <form class="search-box">
            <span class="search-toggle waves-effect waves-light"></span>
            <input type="text" id="search-field">
            <button type="submit" class="search-btn"><i class="flaticon-search100"></i></button>
          </form>
          <div class="nav-close waves-effect waves-light waves-circle" data-offcanvas="close"><i class="flaticon-close47"></i></div>
          <div class="social-buttons">
            <a href="#" class="sb-twitter"><i class="bi-twitter"></i></a>
            <a href="#" class="sb-google-plus"><i class="bi-gplus"></i></a>
            <a href="#" class="sb-facebook"><i class="bi-facebook"></i></a>
          </div>
        </div>
        <a href="index.html" class="offcanvas-logo">
          <div class="icon"><img src="{{ static_url('images/common/logo-big.png') }}" alt="PJ"></div>
          <div class="title">
          FMS
            <span>Fly Management System</span>
          </div>
        </a>
        <a href="#" data-toggle="modal" data-target="#signin-page" data-modal-form="sign-up" class="light-color nav-link" style="font-size:14px;">Đăng ký</a>
        <a href="#" class="btn btn-flat btn-light icon-left waves-effect waves-light"><i class="flaticon-download164"></i> Download (FMS)</a>
      </div>
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
                <li><a href="about.html">Trang chủ</a></li>
                <li><a href="about.html">Giới thiệu</a></li>
                <li class="active"><a href="blog.html">Blog</a></li>
                <li><a href="blog-single.html">eBooks</a></li>
                <li><a href="press.html">Courses</a></li>
                <li><a href="press.html">Portfolio</a></li>
                <li><a href="legal.html">Legals</a></li>
                <li><a href="components.html">Feedback</a></li>
              </ul>
            </nav>
            <!-- Twitter/Blog Tabs -->
            <div class="offcanvas-tabs">
              <ul class="nav-tabs clearfix">
                <li class="active"><a class="waves-effect waves-primary" href="#twitter" data-toggle="tab">Twitter</a></li>
                <li><a class="waves-effect waves-primary" href="#blog" data-toggle="tab">Blog</a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane fade in active" id="twitter">
                  <div class="twitter-feed">
                    <div class="tweet">
                      <a href="#" class="author">@bedismo</a>
                      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor et <a href="#">#magna aliqua</a>.</p>
                    </div>
                    <div class="tweet">
                      <a href="#" class="author">@bedismo</a>
                      <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.</p>
                    </div>
                    <a href="#" class="text-smaller">Follow us on twitter</a>
                  </div>
                </div>
                <div class="tab-pane fade" id="blog">
                  <div class="offcanvas-posts">
                    <a href="blog-single.html" class="post">
                      Check Release
                      <span>Lorem ipsum dolor sit</span>
                    </a>
                    <a href="blog-single.html" class="post">
                      New App Skin Available
                      <span>Tenetur omnis sit odit velit quaerat deserunt cupiditate.</span>
                    </a>
                    <a href="blog-single.html" class="post">
                      Change is Coming
                      <span>Sed ut perspiciatis unde omnis</span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!-- Off-canvas Navigation End -->

    <!--Modal (Signin/Signup Page)-->
    <div class="modal fade" id="signin-page">
      <div class="modal-dialog">
        <div class="modal-form">
          <div class="tab-content">
            <!-- Sign in form -->
            {% include "layouts/signin.volt" %}

            <!-- Sign up form -->
            {% include "layouts/signup.volt" %}
          </div>
          <!-- Hidden real nav tabs -->
          <ul class="nav-tabs hidden">
            <li id="form-1"><a href="#signup-form" data-toggle="tab">Đăng ký</a></li>
            <li id="form-2"><a href="#signin-form" data-toggle="tab">Đăng nhập</a></li>
          </ul>
        </div>
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Navbar -->
    <header class="navbar navbar-fixed-top">
      <div class="container">
        <!-- Nav Toggle -->
        <div class="nav-toggle waves-effect waves-light waves-circle" data-offcanvas="open"><i class="flaticon-menu55"></i></div>
        <!-- Logo -->
        <a href="index.html" class="logo">
          <img src="{{ static_url('images/common/logo-small.png') }}" alt="PhalconPHP Jumpstart">
          PhalconPHP Jumpstart
        </a>
        <!-- Toolbar -->
        <div class="toolbar">
          <a href="#" class="btn btn-flat btn-light icon-left waves-effect waves-light"><i class="flaticon-download164"></i> Download (FMS)</a>
          <a href="#" data-toggle="modal" data-target="#signin-page" data-modal-form="sign-in" class="action-btn">Đăng nhập</a>
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
    <div class="page-heading text-right">
      <div class="container">
        <form class="search-field form-control">
          <button type="submit" class="search-btn"><i class="flaticon-search100"></i></button>
          <input type="text" id="search-input">
          <label for="search-input">Tìm kiếm</label>
        </form>
        <h2>Blog</h2>
      </div>
    </div>

    {% block content %}{% endblock %}

    <!-- Footer -->
    <footer class="footer fw-bg top-inner-shadow padding-top-3x">
      <div class="container space-top">

        <div class="body padding-top-1x">
          <!-- Copyright -->
          <div class="column copyright">
            <p>2015 &copy; <a href="#" target="_blank">Phalcon Jumpstart</a>.com</p>
          </div>

          <nav class="column footer-nav">
            <ul>
              <li><a href="about.html">Trang chủ</a></li>
              <li><a href="blog-single.html">eBooks</a></li>
              <li><a href="press.html">Courses</a></li>
              <li><a href="press.html">Portfolio</a></li>
              <li><a href="components.html">Feedback</a></li>
            </ul>
          </nav>
        </div>
      </div>
    </footer><!-- Footer End -->

    <script type="text/javascript" src="{{ static_url('js/common/jquery-2.1.3.min.js') }}"></script>
    <script type="text/javascript" src="{{ static_url('js/common/bootstrap.min.js') }}"></script>
    <!-- Javascript (jQuery) Libraries and Plugins -->
    <script type="text/javascript" src="{{ static_url('min/index.php?g=jsCommon&rev=' ~ config.jsVersion) }}"></script>

  </body><!-- Body End-->
</html>