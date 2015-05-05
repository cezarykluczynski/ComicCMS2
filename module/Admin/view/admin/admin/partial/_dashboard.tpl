<div ng-app="admin-dashboard" ng-controller="DashboardController as dashboard">
    <div class="row">
        <div ng-init='widgets = {$dashboardWidgets|@json_encode}'></div>
        <div ng-repeat='widget in widgets track by $id(widget.id)' class="widget">
            <div class="col-md-4">
                <div class="well">
                    <h2>{literal}{{widget.name}}{/literal}</h2>
                    <div ng-bind-html="loadWidgetContents(widget)">
                        {literal}{{widget.contents}}{/literal}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>