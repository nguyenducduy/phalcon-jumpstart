{% extends "layouts/main.volt" %}

{% block content %}
    <!-- Blog Grid -->
    <section class="space-top padding-bottom">
      <div class="container">
        <div class="masonry-grid">
          <div class="grid-sizer"></div>
          <div class="gutter-sizer"></div>
          <!-- Item -->
          {% for post in myPost.items %}
          <div class="item">
            <div class="post-tile">
              {% if post.cover|length > 0 %}
              <a href="{{ url(post.slug) }}" class="post-thumb waves-effect">
                <img src="{{ static_url(post.cover) }}" alt="{{ post.title }}">
              </a>
              {% endif %}
              <div class="post-body">
                <div class="post-title">
                  <a href="{{ url(post.slug) }}"><h3>{{ post.title }}</h3></a>
                  <span>{{ post.summary }}</span>
                </div>
                <div class="post-meta">
                  <div class="column">
                    <span>In </span><a href="{{ url('?orderby=datecreated&ordertype=desc&pcid=' ~ post.pcid) }}">{{ post.getCategoryName() }}</a>&nbsp;&nbsp;&nbsp;&nbsp;by <a href="#">{{ post.getAuthorName() }}</a>
                  </div>
                  <div class="column text-right">
                    <span>{{ date('F j, Y', post.datecreated) }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          {% endfor %}
        </div>
        <!-- Pagination -->
        <div class="pagination space-top-3x space-bottom-3x">
          {% if paginator.items is defined and paginator.total_pages > 1 %}
              {% include "layouts/bootstrap-paginator-normal.volt" %}
          {% endif %}
        </div>
      </div>
    </section><!-- Blog Grid End -->
{% endblock %}
