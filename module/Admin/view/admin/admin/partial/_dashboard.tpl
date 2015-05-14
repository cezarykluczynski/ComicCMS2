<div ng-app="admin-dashboard" ng-controller="DashboardController as dashboard">
    <div class="row">
        {foreach from=$dashboardWidgets item=widget}
            <div class="col-md-4 widget" controller="{$widget['controller']}">
            <div class="well">
                <h2>{$widget['name']}</h2>
                <div class="widget-contents">
                    {$this->partial($widget['template'], [
                        'comics' => $comics
                    ])}
                </div>
            </div>
            </div>
        {/foreach}
    </div>
</div>