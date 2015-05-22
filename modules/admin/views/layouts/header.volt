<header>
    <div class="headerwrapper">
        <div class="header-left">
            <a href="index.html" class="logo">
                <img src="{{ static_url('/images/logo.png') }}" alt="Phalcon Jumpstart" width="30" />
            </a>
            <div class="pull-right">
                <a href="#" class="menu-collapse">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
        </div><!-- header-left -->

        <div class="header-right">
            <ul class='breadcrumb'>
                {% for bc in breadcrumb %}
                    {% if (bc['active']) %}
                        <li class="active">{{ bc['text'] }}</li>
                    {% else %}
                        <li><a href='{{ bc['link'] }}'>{{ bc['text'] }}</a></li>
                    {% endif %}
                {% endfor %}
            </ul>

            <div class="pull-right">
                <div class="btn-group btn-group-option">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-caret-down"></i>
                    </button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li><a href="{{ url('/admin/user/edit/id/' ~ session.get('me').id ~ '/redirect/' ~ redirectUrl) }}"><i class="glyphicon glyphicon-lock"></i> Change password</a></li>
                        <li><a href="{{ url('/admin/user/edit/id/' ~ session.get('me').id ~ '/redirect/' ~ redirectUrl) }}"><i class="glyphicon glyphicon-user"></i> Edit profile</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ url('/admin/logout') }}"><i class="glyphicon glyphicon-log-out"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>