<div class="leftpanel">
    <div class="media profile-left">
        <a class="pull-left profile-thumb" href="{{ config.app_baseUri }}admin/profile">
            <img class="img-circle" src="{{ config.app_baseUri ~ session.get('me').avatar }}" alt="">
        </a>
        <div class="media-body">
            <h4 class="media-heading">{{ session.get('me').name }}</h4>
            <small class="text-muted">{{ session.get('me').role }}</small>
        </div>
    </div><!-- media -->

    <h5 class="leftpanel-title">General</h5>

    <ul class="nav nav-pills nav-stacked">
        <li>
            <a href="{{ config.app_baseUri }}admin"><i class="fa fa-home"></i> <span>Home</span></a>
        </li>

        <li class="parent"><a href="#"><i class="fa fa-gears"></i> <span>Admin Tools</span></a>
            <ul class="children">
                <li id="codegenerator_list"><a href="{{ config.app_baseUri }}admin/codegenerator">Code Generator</a></li>
                <li id="logs_list"><a href="{{ config.app_baseUri }}admin/logs?orderby=datecreated&ordertype=desc">Log Tracking</a></li>
            </ul>
        </li>

        <!-- <li class="parent"><a href="#"><i class="fa fa-rocket"></i> <span>API Documentation</span></a>
            <ul class="children">
                <li id="apidocumentation_add"><a href="{{ config.app_baseUri }}admin/apidocumentation/add"><i class="fa fa-plus-circle"></i>&nbsp; Add</a></li>
                <li id="apidocumentation_list"><a href="{{ config.app_baseUri }}admin/apidocumentation">List</a></li>
            </ul>
        </li> -->

        <li class="parent"><a href="#"><i class="fa fa-users"></i> <span>User</span></a>
            <ul class="children">
                <li id="user_add"><a href="{{ config.app_baseUri }}admin/user/add"><i class="fa fa-plus-circle"></i>&nbsp; Add</a></li>
                <li id="user_list"><a href="{{ config.app_baseUri }}admin/user">List</a></li>
            </ul>
        </li>
    </ul>
</div><!-- leftpanel -->