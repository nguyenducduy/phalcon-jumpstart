{% extends "layouts/main.volt" %}

{% block content %}
    <!-- Blog Grid -->
    <section class="space-top padding-bottom">
      <div class="container">
        <div class="masonry-grid">
          <div class="grid-sizer"></div>
          <div class="gutter-sizer"></div>
          <!-- Item -->
          <div class="item w2">
            <div class="post-tile">
              <a href="blog-single.html" class="post-thumb waves-effect">
                <img src="/public/uploads/post/post01.jpg" alt="Post 1">
              </a>
              <div class="post-body">
                <div class="post-title">
                  <a href="blog-single.html"><h3>Change Is Coming. No Regrets.</h3></a>
                  <span>Our powers is unlimitless, faith is strong.</span>
                </div>
                <div class="post-meta">
                  <div class="column">
                    <span>In </span><a href="#">Design</a>&nbsp;&nbsp;&nbsp;&nbsp;by <a href="#">Bedismo</a>
                  </div>
                  <div class="column text-right">
                    <span>January 14, 2015</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Item -->
          <div class="item">
            <div class="post-tile">
              <a href="blog-single.html" class="post-thumb waves-effect">
                <img src="/public/uploads/post/post02.jpg" alt="Post 2">
              </a>
              <div class="post-body">
                <div class="post-title">
                  <a href="blog-single.html"><h3>Lifetime Happines</h3></a>
                  <span>Summer mood</span>
                </div>
                <div class="post-meta">
                  <div class="column">
                    <span>In </span><a href="#">Design</a>&nbsp;&nbsp;&nbsp;&nbsp;by <a href="#">Bedismo</a>
                  </div>
                  <div class="column text-right">
                    <span>October 08, 2014</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Item -->
          <div class="item">
            <div class="post-tile">
              <div class="post-body">
                <div class="post-title">
                  <a href="blog-single.html"><h3>How To Make Design?</h3></a>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur porro quisquam est, qui dolorem ipsum quia...</p>
                </div>
                <div class="post-meta">
                  <div class="column">
                    <span>In </span><a href="#">Design</a>&nbsp;&nbsp;&nbsp;&nbsp;by <a href="#">Bedismo</a>
                  </div>
                  <div class="column text-right">
                    <span>August 23, 2014</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Item -->
          <div class="item">
            <div class="post-tile">
              <a href="blog-single.html" class="post-thumb waves-effect">
                <img src="/public/uploads/post/post03.jpg" alt="Post 3">
              </a>
              <div class="post-body">
                <div class="post-title">
                  <a href="blog-single.html"><h3>Glass Vs Plastics</h3></a>
                  <span>Make better choice</span>
                </div>
                <div class="post-meta">
                  <div class="column">
                    <span>In </span><a href="#">Design</a>&nbsp;&nbsp;&nbsp;&nbsp;by <a href="#">Bedismo</a>
                  </div>
                  <div class="column text-right">
                    <span>August 17, 2014</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="post-tile">
              <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/09R8_2nJtjg" frameborder="0" width="500" height="281" allowfullscreen></iframe>
              </div>
              <div class="post-body">
                <div class="post-title">
                  <a href="blog-single.html"><h3>Video Showcase</h3></a>
                </div>
                <div class="post-meta">
                  <div class="column">
                    <span>In </span><a href="#">Video</a>&nbsp;&nbsp;&nbsp;&nbsp;by <a href="#">Bedismo</a>
                  </div>
                  <div class="column text-right">
                    <span>July 06, 2014</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Pagination -->
        <div class="pagination space-top-3x space-bottom-3x">
          <div class="page-slider">
            <span></span>
            <input data-slider-id='ex1Slider' type="text" data-slider-min="1" data-slider-max="22" data-slider-step="1" data-slider-value="13"/>
          </div>
          <div class="controls">
            <a href="#">Older</a>
            <a href="#">Newer</a>
          </div>
        </div>
      </div>
    </section><!-- Blog Grid End -->
{% endblock %}
