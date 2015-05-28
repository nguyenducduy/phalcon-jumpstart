{% extends "layouts/main.volt" %}

{% block content %}

    <div id="preloader">
      <div id="status">&nbsp;</div>
      <noscript>JavaScript is off. Please enable to view full site.</noscript>
    </div>

    <div class="wrapper">
      <div class="container">

        <header id="home">

          <div class="head-image bg-image" data-bg-image="{{ static_url('images/profile/head-image.jpg') }}">
            <div class="avatar wow bounceInDown" >
              <img alt="avatar" src="{{ static_url('images/profile/avatar.jpg') }}" />
            </div>
          </div>

          <div class="sticky-paper-head has-shadow">
            <div class="content">
              <div class="social-icons">
                <ul>
                  <li>
                    <a class="fa fa-twitter" href="https://twitter.com/nguyenducduyit" target="_blank"></a>
                  </li>
                  <li>
                    <a class="fa fa-google-plus" href="https://plus.google.com/u/0/102420132083604667192/posts" target="_blank"></a>
                  </li>
                  <li>
                    <a class="fa fa-github" href="https://github.com/nguyenducduy" target="_blank"></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="col-xs-12  nav-menu top-menu-holder">
            <nav class="hidden-xs visible-lg ">
              <ul class="nav">
                  <li class="active">
                  <a  href="{{ url('nguyenducduy') }}">home</a>
                </li>
                <li>
                  <a  href="#profile">profile</a>
                </li>
                <li>
                  <a  href="#education">education</a>
                </li>
                <li>
                  <a  href="#experiences">experiences</a>
                </li>
                 <li>
                  <a  href="#skills">skills</a>
                </li>
                <li>
                  <a  href="#portfolio">portfolio</a>
                </li>
                <!-- <li>
                  <a  href="#contact">contact</a>
                </li>
 -->              </ul>

            </nav>

            <select class="top-drop-menu nav visible-md visible-sm visible-xs hidden-lg">
              <option value="#home" selected="selected">
                Home
              </option>

              <option value="#profile">
                Profile
              </option>
              <option value="#education">
                Education
              </option>
              <option value="#experiences">
                Experiences
              </option>

 <option value="#skills">
                Skills
              </option>

              <option value="#portfolio">
                Portfolio
              </option>

              <option value="#contact">
                Contact
              </option>

            </select>
          </div>

        </header>
        <section id="info" class="big-name">
          <div class="sticky-paper-head has-shadow ">
            <div class="content">
              <h1>Nguyen Duc Duy</h1>
              <div class="short-tag">
                PHP Development and Operators
              </div>
            </div>
          </div>
          <div class="sticky-paper-body has-shadow " >
            <div class="content">
              <div class="row">
                <div class="col-xs-12 col-md-7">
                  <div class="le-paragraph">
                    <p>
                      <strong>My name's Duy,</strong>
                      I'm a PHP DevOps and living in Vietnam country. I started develop the website project from 3 years ago with PHP language.
                      Now, i'm focus on website scalable, performance stress test, system scalable, javascript animation, single page apps and service monitoring.
                    </p>
                  </div>
                </div>
                <div class="col-xs-12 col-md-5">
                  <div class="tabled-info-holder">
                    <ul class="info-holder">
                      <li>
                        <div class="info-item">
                          <i class="fa fa-envelope"></i>
                          <span><a href="mailto:nguyenducduy.it@gmail.com">nguyenducduy.it@gmail.com</a></span>
                        </div>
                      </li>
                      <li>
                        <div class="info-item">
                          <i class="fa fa-twitter"></i>
                          <span>Twitter: <a href="https://twitter.com/nguyenducduyit" target="_blank">@nguyenducduyit</a></span>
                        </div>
                      </li>

                    </ul>

                    <ul class="info-holder">
                      <li>
                        <div class="info-item">
                          <i class="fa fa-map-marker"></i>
                          <span>Live in: Ho Chi Minh,Vietnam</span>
                        </div>
                      </li>
                      <li>
                        <div class="info-item">
                          <i class="fa fa-phone"></i>
                          <span>+84 (92) 5511079</span>
                        </div>
                      </li>
                    </ul>
                    <div class="social-icons small">
                      <ul>
                        <li>
                          <a class="fa fa-twitter" href="https://twitter.com/nguyenducduyit" target="_blank"></a>
                        </li>
                        <li>
                          <a class="fa fa-google-plus" href="https://plus.google.com/u/0/102420132083604667192/posts" target="_blank"></a>
                        </li>
                        <li>
                          <a class="fa fa-github" href="https://github.com/nguyenducduy" target="_blank"></a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <hr class="le-hr">

        <section id="profile" >
          <div class="sticky-paper-head has-shadow ">
            <div class="content">
              <h1>profile</h1>
            </div>
          </div>
          <div class="sticky-paper-body has-shadow " >
            <div class="content">
              <div class="row">
                <div class="col-xs-12 col-lg-6">
                  <div class="timeline-holder wow bounceInUp">
                    <div class="timeline-head">
                      <div class="icon">
                        <i class="fa fa-user"></i>
                      </div>
                    </div>
                    <div class="timeline-body">
                      <div class="profile-item timeline-item">
                        <div class="icon-item left">
                          <div class="circular-icon ">
                            <i class="fa fa-child"></i>
                          </div>
                        </div>

                        <div class="profile-content timeline-content right">
                          <label>full name</label>
                          <div class="value">Nguyen Duc Duy</div>
                        </div>

                      </div>

                      <div class="profile-item timeline-item">
                        <div class="icon-item right">
                          <div class="circular-icon">
                            <i class="fa fa-envelope"></i>
                          </div>

                        </div>
                        <div class="profile-content timeline-content left">
                          <label>email address</label>
                          <div class="value">nguyenducduy.it@gmail.com
                          </div>
                        </div>

                      </div>

                      <div class="profile-item timeline-item">
                        <div class="icon-item left">
                          <div class="circular-icon">
                            <i class="fa fa-birthday-cake"></i>
                          </div>

                        </div>
                        <div class="profile-content timeline-content right">
                          <label>birthday</label>
                          <div class="value">10/11/1988</div>
                        </div>

                      </div>


                      <div class="profile-item timeline-item">
                        <div class="icon-item right">
                          <div class="circular-icon ">
                            <i class="fa fa-phone"></i>
                          </div>
                        </div>

                        <div class="profile-content timeline-content left">
                          <label>Phone</label>
                          <div class="value">+84 92 5511079</div>
                        </div>

                      </div>


                      <div class="profile-item timeline-item">
                        <div class="icon-item left">
                          <div class="circular-icon left">
                            <i class="fa fa-code"></i>
                          </div>

                        </div>
                        <div class="profile-content timeline-content right">
                          <label>Availability</label>
                          <div class="value">Not available for Full-Time</div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-xs-12 col-lg-6">
                  <div class="interests-holder">
                    <div class="interests-row medium">
                      <ul>
                        <li class="wow bounceInDown" data-wow-delay="0.1s" >
                          <i class="fa fa-code"></i>
                          <span>Coding</span>
                        </li>
                        <li class="wow bounceInDown" data-wow-delay="0.2s">
                          <i class="fa fa-cutlery"></i>
                          <span>Foods</span>
                        </li>
                        <li class="wow bounceInDown" data-wow-delay="0.3s">
                          <i class="fa fa-graduation-cap"></i>
                          <span>Science</span>
                        </li>
                        <li class="wow bounceInDown" data-wow-delay="0.4s">
                          <i class="fa fa-leaf"></i>
                          <span>Nature</span>
                        </li>
                      </ul>
                    </div>
                    <div class="interests-row big">
                      <ul>
                        <li class="wow bounceInDown" data-wow-delay="0.5s">
                          <i class="fa fa-music"></i>
                          <span>Music</span>
                        </li>
                        <li class="wow bounceInDown" data-wow-delay="0.6s">
                          <i class="fa fa-plane"></i>
                          <span>Travel</span>
                        </li>
                        <li class="wow bounceInDown" data-wow-delay="0.7s">
                          <i class="fa fa-pencil"></i>
                          <span>Writing</span>
                        </li>
                        <li class="wow bounceInDown" data-wow-delay="0.8s">
                          <i class="fa fa-coffee"></i>
                          <span>Coffee</span>
                        </li>
                      </ul>
                    </div>


                    <div class="title wow fadeIn"  >
                      <h1>hobbies & interests</h1>
                    </div>
                    <div class="interests-row big">
                      <ul>
                        <li class="wow bounceInDown" data-wow-delay="0.1s">
                          <i class="fa fa-rocket"></i>
                          <span>Space</span>
                        </li>
                        <li class="wow bounceInDown" data-wow-delay="0.2s">
                          <i class="fa fa-road"></i>
                          <span>road Trip</span>
                        </li>
                        <li class="wow bounceInDown" data-wow-delay="0.3s">
                          <i class="fa fa-paw"></i>
                          <span>dogs</span>
                        </li>
                        <li class="wow bounceInDown" data-wow-delay="0.4s">
                          <i class="fa fa-paint-brush"></i>
                          <span>art</span>
                        </li>
                      </ul>
                    </div>

                    <div class="interests-row medium">
                      <ul>
                        <li class="wow bounceInDown" data-wow-delay="0.5s">
                          <i class="fa fa-music"></i>
                          <span>Music</span>
                        </li>
                        <li class="wow bounceInDown" data-wow-delay="0.6s">
                          <i class="fa fa-plane"></i>
                          <span>Travel</span>
                        </li>
                        <li class="wow bounceInDown" data-wow-delay="0.7s">
                          <i class="fa fa-pencil"></i>
                          <span>Writing</span>
                        </li>
                        <li class="wow bounceInDown" data-wow-delay="0.8s">
                          <i class="fa fa-coffee"></i>
                          <span>Coffee</span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <hr class="le-hr">

        <section id="education" >
          <div class="sticky-paper-head has-shadow ">
            <div class="content">
              <h1>education</h1>
            </div>
          </div>

          <div class="sticky-paper-body has-shadow " >
            <div class="content">
              <div class="row">
                <div class="col-xs-12">
                  <div class="timeline-holder wow bounceInUp" >
                    <div class="timeline-head event-head">
                      <div class="icon">
                        <i class="fa fa-university"></i>
                      </div>
                    </div>
                    <div class="timeline-body">
                      <div class="event-item timeline-item">

                        <div class="event-title left">
                          <h1>Software Engineer</h1>
                          <h4>NIIT College, Vietnam</h4>
                        </div>
                        <div class="event-content timeline-content right">
                          <p>
                            09/2008 — 09/2011
                          </p>
                        </div>
                      </div>
                      <div class="event-item timeline-item">
 <div class="event-content timeline-content left">
                          <p>
                            01/2012 — 06/2012
                          </p>
                        </div>
                        <div class="event-title right">
                          <h1>PHP Programming Language</h1>
                          <h4>Cisnet Center, Vietnam</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <hr class="le-hr">

        <section id="experiences" >
          <div class="sticky-paper-head has-shadow ">
            <div class="content">
              <h1>experiences</h1>
            </div>
          </div>

          <div class="sticky-paper-body has-shadow " >
            <div class="content">
              <div class="row">
                <div class="col-xs-12">
                  <div class="timeline-holder wow bounceInUp" >
                    <div class="timeline-head event-head">
                      <div class="icon">
                        <i class="fa fa-suitcase"></i>
                      </div>
                    </div>
                    <div class="timeline-body">
                      <div class="event-item timeline-item">
                        <div class="event-title left">
                          <h1>PHP DevOps</h1>
                          <h4>The Gioi Di Dong Company <small>12/2012 — 02/2014</small></h4>
                        </div>
                        <div class="event-content timeline-content right">
                          <p>
                            Develop the E-commerce website dienmay.com. </br> Maintenance and optimize website for high traffic user.
                          </p>
                        </div>
                      </div>
                      <div class="event-item timeline-item">
                        <div class="event-content timeline-content left">
                          <p>
                          Config and manage linux system cluster. </br>
                          Load balancing HTTP request, MySQL database, working with Amazon EC2, Red5 video streaming, system call center Elastix.
                          </p>
                        </div>
                            <div class="event-title right">
                          <h1>PHP DevOps</h1>
                          <h4>Spiral Outsourcing Company <small>02/2014 — 02/2015</small></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>


        <hr class="le-hr">

        <section id="skills" >
          <div class="sticky-paper-head has-shadow ">
            <div class="content">
              <h1>skills</h1>
            </div>
          </div>

          <div class="sticky-paper-body has-shadow " >
            <div class="content">
              <div class="row">
                <div class="col-xs-12">
                  <div class="timeline-holder wow bounceInUp" >
                    <div id="skills-head" class="timeline-head">
                      <div class="icon">
                        <i class="fa  fa-tasks"></i>
                      </div>
                    </div>
                    <div class="timeline-body">
                      <div class="skill-holder timeline-item">

                        <div class="skill-item left">
                          <div class="skill"  data-text="80%" data-info="Linux"   data-percent="80"   data-fill="#272425"></div>
                        </div>

                        <div class="event-content timeline-content right">
                          <p>
                           CentOS 6 </br>
                           Fedora 19
                          </p>
                        </div>

                      </div>


                      <div class="skill-holder timeline-item">



                        <div class="event-content timeline-content left">
                          <p>
                            HTML <br>
                            HTML 5
                          </p>
                        </div>

                          <div class="skill-item right">
                          <div class="skill"  data-text="75%" data-info="HTML"   data-percent="75"   data-fill="#272425"></div>
                        </div>

                      </div>


                      <div class="skill-holder timeline-item">
                        <div class="skill-item left">
                          <div class="skill"  data-text="75%" data-info="CSS"   data-percent="75"   data-fill="#272425"></div>
                        </div>
                        <div class="event-content timeline-content right">
                          <p>
                           CSS <br>
                           CSS 3
                          </p>
                        </div>
                      </div>

                      <div class="skill-holder timeline-item">
                        <div class="event-content timeline-content left">
                          <p>
                            jQuery <br>
                            Javascript
                          </p>
                        </div>
                          <div class="skill-item right">
                          <div class="skill"  data-text="75%" data-info="jQuery"   data-percent="75"   data-fill="#272425"></div>
                        </div>
                      </div>

                        <div class="skill-holder timeline-item">
                            <div class="skill-item left">
                              <div class="skill"  data-text="80%" data-info="PHP"   data-percent="75"   data-fill="#272425"></div>
                            </div>
                            <div class="event-content timeline-content right">
                              <p>
                               PHP 5
                              </p>
                            </div>
                          </div>

                          <div class="skill-holder timeline-item">
                        <div class="event-content timeline-content left">
                          <p>
                            MySQL
                          </p>
                        </div>
                          <div class="skill-item right">
                          <div class="skill"  data-text="75%" data-info="MySQL"   data-percent="75"   data-fill="#272425"></div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>


        <hr class="le-hr">

        <section id="portfolio" >
          <div class="sticky-paper-head has-shadow ">
            <div class="content">
              <h1>portfolio</h1>
            </div>
          </div>

          <div class="sticky-paper-body has-shadow " >
            <div class="content">
              <div class="row">
                <div class="col-xs-12">
                  <div class="portfolio-holder">
                    <div id="filters" class="group-selectors">
                      <a class="active" href="#all"   data-filter="*">web</a>
                    </div>

                    <div id="portfolio-grid" class="row">
                      <figure class="col-xs-12 col-sm-6 col-md-4 portfolio-item illustration ">
                        <a href="http://phalconjumpstart.com" target="_blank">
                          <img alt="" src="{{ static_url('images/logo-brand.png') }}"   />
                        </a>
                        <div class="holder">
                          <div class="paper  has-shadow title">
                            <div class="content">
                            Phalcon Jumpstart
                            </div>
                          </div>
                          <div class="paper  has-shadow category">
                            <div class="content">
                                Open source
                            </div>
                          </div>
                        </div>
                      </figure>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <!-- <hr class="le-hr">


        <section id="contact" >
          <div class="sticky-paper-head has-shadow ">
            <div class="content">
              <h1>contact</h1>
            </div>
          </div>

          <div class="sticky-paper-body has-shadow " >
            <div class="content">
              <div class="row row-contact">

                <div class="col-xs-12 col-md-8">
                  <div class="contact-form-holder">

                    <div class="message-box"></div>

                    <form id="contact-form" class="contact-form " method="post" >




                      <div class="col-xs-12 col-sm-6 no-margin">
                        <div class="control-group">

                          <div class="controls">


                            <input  id="cname" data-placeholder="Your Name*" name="name" size="25" class="le-input  required col-xs-12"  />

                          </div>
                        </div>
                      </div>

                      <div class="col-xs-12 col-sm-6 no-margin">
                        <div class="control-group">

                          <div class="controls">


                            <input id="cemail" data-placeholder="Your Email*" name="email" class="le-input  required email col-xs-12" />
                          </div>
                        </div>
                      </div>


                      <div class="col-xs-12  no-margin">
                        <div class="control-group">

                          <div class="controls">


                            <input  id="subject" data-placeholder="Subject" name="subject" size="25" class="le-input col-xs-12"  />

                          </div>
                        </div>
                      </div>


                      <div class="col-xs-12 no-margin">
                        <div class="control-group">

                          <div class="controls">


                            <textarea id="ccomment" class=" le-input col-xs-12 no-margin" name="message" rows="9"  cols="5" data-placeholder="Message" ></textarea>


                          </div>
                        </div>
                      </div>



                      <div class="col-xs-12 no-margin">
                        <div class="button-holder">
                          <input class="submit  le-btn big" type="submit" value="send" />
                        </div>
                        <div id="loading" class="pull-right">
                          <img alt="" src="images/loader.gif" />
                        </div>
                      </div>





                    </form>
                  </div>
                </div>

                <div class="col-xs-12 col-md-4">
                  <div class="contact-info">

                    <ul>
                      <li>
                        <label>address:</label> Ataturk Blvd, 6209 St, Suite 877<br>Istanbul,Turkey
                      </li>
                      <li>
                        <label>email:</label> <a href="#">info@example.com</a>
                      </li>
                      <li>
                        <label>phone:</label> <a href="#">+90 555 111 2233</a>
                      </li>
                      <li>
                        <label>fax</label> <a href="#">+90 123 456 7890</a>
                      </li>
                    </ul>

                    <div class="social-icons">
                      <ul>
                        <li>
                          <a class="fa fa-facebook" href="#"></a>
                        </li>
                        <li>
                          <a class="fa fa-twitter" href="#"></a>
                        </li>
                        <li>
                          <a class="fa fa-google-plus" href="#"></a>
                        </li>
                        <li>
                          <a class="fa fa-youtube" href="#"></a>
                        </li>
                        <li>
                          <a class="fa fa-linkedin" href="#"></a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </section>

 -->

        <section id="footer">
          <div class="sticky-paper-head has-shadow">
            <div class="content">
              <div class="copyright">
                Powered by <a class="bold" href="http://phalconjumpstart.com">Phalcon Jumpstart</a>
              </div>
            </div>
          </div>
        </section>


      </div>

    </div>

    <a class="goto-top" href="#gotop"></a>

{% endblock %}
