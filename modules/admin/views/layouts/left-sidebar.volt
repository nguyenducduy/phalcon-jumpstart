<div class="leftpanel">
    <div class="media profile-left">
        <a class="pull-left profile-thumb" href="{{ url('/admin/user/edit/id/' ~ session.get('me').id ~ '/redirect/' ~ redirectUrl) }}">
            <img class="img-circle" src="{{ static_url(session.get('me').avatar) }}" alt="">
        </a>
        <div class="media-body">
            <h4 class="media-heading">{{ session.get('me').name }}</h4>
            <small class="text-muted">{{ session.get('me').roleName }}</small>
        </div>
    </div><!-- media -->

    <h5 class="leftpanel-title">General</h5>

    <ul class="nav nav-pills nav-stacked">
        <li>
            <a href="{{ url('/admin') }}"><i class="fa fa-home"></i> <span>Home</span></a>
        </li>

        <li class="parent"><a href="#"><i class="fa fa-gears"></i> <span>Admin Tools</span></a>
            <ul class="children">
                <li id="codegenerator_list"><a href="{{ url('/admin/codegenerator') }}">Code Generator</a></li>
                <li id="logs_list"><a href="{{ url('/admin/logs?orderby=datecreated&ordertype=desc') }}">Log Tracking</a></li>
            </ul>
        </li>

        <li class="parent"><a href="#"><i class="fa fa-users"></i> <span>User</span></a>
            <ul class="children">
                <li id="user_add"><a href="{{ url('/admin/user/add/redirect/' ~ redirectUrl) }}"><i class="fa fa-plus-circle"></i>&nbsp; Add</a></li>
                <li id="user_list"><a href="{{ url('/admin/user') }}">List</a></li>
            </ul>
        </li>
    </ul>
</div><!-- leftpanel -->