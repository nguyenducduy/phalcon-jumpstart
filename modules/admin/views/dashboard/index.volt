{% extends "layouts/main.volt" %}

{% block content %}
<div class="contentpanel" rel="dashboard_list">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th colspan="2"><h1>{{ lang._('LabelSystemInformation') }}</h1></th>
                    </tr>
                </thead>
                <tr>
                    <td width="200" class="td_right">{{ lang._('LabelServerIp') }} :</td>
                    <td>{{ formData['fserverip'] }}</td>
                </tr>
                <tr>
                    <td width="200" class="td_right">{{ lang._('LabelClientIp') }} :</td>
                    <td>{{ formData['fclientip'] }}</td>
                </tr>
                <tr>
                    <td class="td_right">{{ lang._('LabelServerName') }} :</td>
                    <td>{{ formData['fserver'] }}</td>
                </tr>
                <tr>
                    <td class="td_right">{{ lang._('LabelPhpVersion') }} :</td>
                    <td>{{ formData['fphp'] }}</td>
                </tr>
                <tr>
                    <td class="td_right">{{ lang._('LabelUserAgent') }} :</td>
                    <td>{{ formData['fuseragent'] }}</td>
                </tr>

                <tr>
                    <td class="td_right">{{ lang._('LabelServerTime') }} :</td>
                    <td>{{ formData['now'] }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
{% endblock %}