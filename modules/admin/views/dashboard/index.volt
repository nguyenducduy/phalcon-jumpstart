{% extends "layouts/main.volt" %}

{% block content %}
<div class="contentpanel" rel="dashboard_list">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th colspan="2"><h1>{{ lang.get('LabelSystemInformation') }}</h1></th>
                    </tr>
                </thead>
                <tr>
                    <td width="200" class="td_right">{{ lang.get('LabelServerIp') }} :</td>
                    <td>{{ formData['fserverip'] }}</td>
                </tr>
                <tr>
                    <td width="200" class="td_right">{{ lang.get('LabelClientIp') }} :</td>
                    <td>{{ formData['fclientip'] }}</td>
                </tr>
                <tr>
                    <td class="td_right">{{ lang.get('LabelServerName') }} :</td>
                    <td>{{ formData['fserver'] }}</td>
                </tr>
                <tr>
                    <td class="td_right">{{ lang.get('LabelPhpVersion') }} :</td>
                    <td>{{ formData['fphp'] }}</td>
                </tr>
                <tr>
                    <td class="td_right">{{ lang.get('LabelUserAgent') }} :</td>
                    <td>{{ formData['fuseragent'] }}</td>
                </tr>

                <tr>
                    <td class="td_right">{{ lang.get('LabelServerTime') }} :</td>
                    <td>{{ formData['now'] }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
{% endblock %}