<div class="page-slider">
    <span></span>
    <input data-slider-id='ex1Slider' type="text" data-slider-min="1" data-slider-max="{{ paginator.total_pages }}" data-slider-step="1" data-slider-value="{{ paginator.current }}"/>
  </div>
<div class="controls">
    {% set mid_range = 7 %}

    {% if paginator.total_pages > 1 %}
        {% if paginator.current != 1 and paginator.total_items >= 10 %}
            {% set pageString = ''~ linkTo(""~paginateUrl~"&page="~paginator.before, "Newer") ~'' %}
        {% else %}
            {% set pageString = '<span style="display:none">'~ linkTo("#", "Newer") ~'1</span>' %}
        {% endif %}

        {% set start_range = paginator.current - (mid_range / 2)|floor %}
        {% set end_range = paginator.current + (mid_range / 2)|floor %}

        {% if start_range <= 0 %}
            {% set end_range = end_range + (start_range)|abs + 1 %}
            {% set start_range = 1 %}
        {% endif %}

        {% if end_range > paginator.total_pages %}
            {% set start_range = start_range - (end_range - paginator.total_pages) %}
            {% set end_range = paginator.total_pages %}
        {% endif %}

        {% set range = range(start_range, end_range) %}

        {% for i in 1..paginator.total_pages %}
            {% if i == 1 or i == paginator.total_pages or i in range %}
                {% if i == paginator.current %}
                    {% set pageString = pageString ~ '' ~ linkTo(""~paginateUrl~"&page="~i, ""~i) ~ '' %}
                {% endif %}
            {% endif %}
        {% endfor %}

        {% if paginator.current != paginator.total_pages and paginator.total_items >= 10 %}
            {% set pageString = pageString ~ '' ~ linkTo(""~paginateUrl~"&page="~paginator.next, "Older") ~ '' %}
        {% else %}
            {% set pageString = pageString ~ '<span style="display:none">' ~ linkTo("#", "Older") ~ '</span>' %}
        {% endif %}

        {{ pageString }}
    {% endif %}
</div>
