<div -ng-app="admin-dashboard" ng-controller="DashboardController as dashboard">
    <div class="row">
        {foreach from=$dashboardWidgets item=widget}
            <div class="col-md-4 widget" controller="{$widget['controller']}">
            <div class="panel panel-default">
                <h2 class="panel-heading">{$widget['name']}</h2>
                <div class="panel-body widget-contents" ng-include="'{$widget['template']}'"></div>
            </div>
            </div>
        {/foreach}
    </div>
</div>
