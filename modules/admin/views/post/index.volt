{% extends "layouts/main.volt" %}

{% block content %}
<div class="contentpanel" rel="post_list">
    {{ flash.output() }}
    {{ flashSession.output() }}
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row mb20" id="filterBox">
                <div class="col-lg-6 col-md-6 col-sm-12 pull-right text-right">
                    <div class="col-lg-10 col-md-10 col-sm-10">
                    {% if paginator.items is defined and paginator.total_pages > 1 %}
                        {% include "layouts/bootstrap-paginator-normal.volt" %}
                    {% endif %}
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <a href="{{ url('admin/post/add/redirect/' ~ redirectUrl) }}" class="btn btn-sm btn-success addBtn"><i class="fa fa-plus"></i>&nbsp; {{ lang.get('label_add_button') }}</a>
                    </div>
                </div>
                <div class="colg-lg-6 col-md-6 col-sm-12">
                    <div class="input-group input-group-sm">
                        <input type="text" value="{% if formData['conditions']['keyword'] is defined %}{{ formData['conditions']['keyword'] }}{% endif %}" placeholder="Search in Title, " class="form-control" id="keyword">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-white" tabindex="-1" onclick="gosearch()"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            {% if myPost.total_items > 0 %}
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="" onsubmit="return confirm('Are you sure ?');">
                    <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}" />
                    <table class="table table-hover mb30">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="check-all"/></th>
                                <th>User</th>
                                <th>Category</th>
                                <th>
                                    <a href="{{ url.getBaseUri() }}admin/post?orderby=id&ordertype={% if formData['orderType']|lower == 'desc'%}asc{% else %}desc{% endif %}{% if formData['conditions']['keyword'] != '' %}&keyword={{ formData['conditions']['keyword'] }}{% endif %}">
                                        ID
                                    </a>
                                </th>
                                <th>Title</th>
                                <th>Cover</th>
                                <th>Status</th>
                                <th>Type</th>
                                <th>
                                    <a href="{{ url.getBaseUri() }}admin/post?orderby=displayorder&ordertype={% if formData['orderType']|lower == 'desc'%}asc{% else %}desc{% endif %}{% if formData['conditions']['keyword'] != '' %}&keyword={{ formData['conditions']['keyword'] }}{% endif %}">
                                        Display order
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ url.getBaseUri() }}admin/post?orderby=commentcount&ordertype={% if formData['orderType']|lower == 'desc'%}asc{% else %}desc{% endif %}{% if formData['conditions']['keyword'] != '' %}&keyword={{ formData['conditions']['keyword'] }}{% endif %}">
                                        Comment count
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ url.getBaseUri() }}admin/post?orderby=datecreated&ordertype={% if formData['orderType']|lower == 'desc'%}asc{% else %}desc{% endif %}{% if formData['conditions']['keyword'] != '' %}&keyword={{ formData['conditions']['keyword'] }}{% endif %}">
                                        Date created
                                    </a>
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="12">
                                    <div class="bulk-actions">
                                        <select name="fbulkaction" class="col-lg-2 input-sm">
                                            <option value="">{{ lang.get('label_select_action') }}</option>
                                            <option value="delete">{{ lang.get('label_delete_action') }}</option>
                                        </select>
                                        <input type="submit" name="fsubmitbulk" class="btn btn-default btn-sm" value="{{ lang.get('label_submit_button') }}" />
                                    </div>
                                    <div class="clear"></div>
                                </td>
                            </tr>
                        </tfoot>
                        <tbody>
                        {% for post in myPost.items %}
                            <tr>
                                <td>
                                    <input type="checkbox" name="fbulkid[]" value="{{ post.id }}" {% if formData['fbulkid'] is defined %}{% for key, value in formData['fbulkid'] if value == post.id %}checked="checked"{% endfor %}{% endif %} />
                                </td>
                                <td>{{ post.uid }}</td>
                                <td>{{ post.pcid }}</td>
                                <td>{{ post.id }}</td>
                                <td>{{ post.title }}</td>
                                <td>{{ post.cover }}</td>
                                <td><span class="label label-primary">{{ post.getStatusName()|upper }}</span></td>
                                <td><span class="label label-primary">{{ post.getTypeName()|upper }}</span></td>
                                <td>{{ post.displayorder }}</td>
                                <td>{{ post.commentcount }}</td>
                                <td>{{ post.datecreated }}</td>
                                <td>
                                    <div class="btn-group btn-group-xs">
                                        <a href="{{ url('admin/post/edit/id/' ~ post.id ~ '/redirect/' ~ redirectUrl) }}" class="btn btn-white"><i class="fa fa-pencil"></i></a>
                                        <a href="javascript:deleteConfirm('{{ url('admin/post/delete/id/' ~ post.id ~ '/redirect/' ~ redirectUrl) }}', '{{ post.id }}');" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                                    </div>
                                </td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                    </form>
                </div>
            </div>
            {% else %}
            <div class="row">
                <div class="col-md-12">
                    <div class="no-record">
                        <p>No result found!</p>
                        <i class="fa fa-exclamation-circle"></i>
                    </div>
                </div>
            </div>
            {% endif %}
        </div>
    </div>
</div>

<script type="text/javascript">
    function gosearch() {
        var path = root_url + "admin/post";

        var keyword = $("#keyword").val();
        if(keyword.length > 0) {
            path += "?keyword=" + keyword;
        }

        document.location.href= path;
    }
</script>

{% endblock %}
