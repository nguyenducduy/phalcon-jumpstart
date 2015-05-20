
    <style type="text/css">
    .pagination li {
        font-size:12px;
    }
    </style>
    <ul class="pagination" style="margin:0;">
        {% set mid_range = 7 %}

        {% if paginator.total_pages > 1 %}
            {% if paginator.current != 1 and paginator.total_items >= 10 %}
                {% set pageString = '<li>'~ linkTo(""~paginateUrl~"&page="~page.before, "&laquo") ~'</li>' %}
            {% else %}
                {% set pageString = '<li style="display:none">'~ linkTo("#", "&laquo") ~'</li>' %}
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
                        {% set pageString = pageString ~ '<li class="active">'~ linkTo(""~paginateUrl~"&page="~i, ""~i) ~'</li>' %}
                    {% endif %}
                {% endif %}
            {% endfor %}

            {% if paginator.current != paginator.total_pages and paginator.total_items >= 10 %}
                {% set pageString = pageString ~ '<li>'~ linkTo(""~paginateUrl~"&page="~paginator.next, "&raquo") ~'</li>' %}
            {% else %}
                {% set pageString = pageString ~ '<li style="display:none">'~ linkTo("#", "&raquo") ~'</li>' %}
            {% endif %}

            {{ pageString }}
        {% endif %}
    </ul>
