<div ng-app="admin-dashboard" ng-controller="DashboardController as dashboard">
    <div class="row">
        <div ng-init='widgets = {$dashboardWidgets|@json_encode}'></div>
        <div ng-repeat='widget in widgets track by $id(widget.id)'
            class="widget" controller="{literal}{{widget.controller}}{/literal}">
            <div class="col-md-4">
                <div class="well">
                    <h2>{literal}{{ widget.name }}{/literal}</h2>
                    <div ng-bind-html="loadWidgetContents(widget)" class="widget-contents"></div>
                </div>
            </div>
        </div>
    </div>
</div>