<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" type="image/png" href="{{ static_url('images/favicon.ico') }}">
        {{ get_title() }}

        <link href="{{ static_url('/css/common/materialize.min.css') }}" rel="stylesheet">
        <script src="{{ static_url('/min/index.php?g=jquery&rev=' ~ config.jsVersion) }}"></script>

    </head>
    <body>
        {% include "layouts/header.volt" %}
        <div class="container">
            {% block content %}{% endblock %}
        </div>
        {% include "layouts/footer.volt" %}