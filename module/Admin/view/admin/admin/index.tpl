<div ng-app="admin" ng-controller="TabController as panel">
    {$this->partial('partial/_navbar')}
    <ul {literal}ng-init="tab = comics"{/literal} class="nav nav-tabs">
        <li {literal}ng-class="{active:panel.isSelected('dashboard') }"{/literal}>
            <a href ng-click="panel.selectTab('dashboard')">{$this->translate("Dashboard")}</a>
        </li>
        <li {literal}ng-class="{active:panel.isSelected('comics') }"{/literal}>
            <a href ng-click="panel.selectTab('comics')">{$this->translate("Comics")}</a>
        </li>
        <li {literal}ng-class="{active:panel.isSelected('comments') }"{/literal}>
            <a href ng-click="panel.selectTab('comments')">{$this->translate("Comments")}</a>
        </li>
        <li {literal}ng-class="{active:panel.isSelected('users') }"{/literal}>
            <a href ng-click="panel.selectTab('users')">{$this->translate("Users")}</a>
        </li>
    </ul>

    <div ng-show="panel.isSelected('dashboard')">
        {$this->partial('partial/_dashboard')}
    </div>
    <div ng-show="panel.isSelected('comics')">
        {$this->partial('partial/_comics')}
    </div>
    <div ng-show="panel.isSelected('comments')">
        Comments
    </div>
    <div ng-show="panel.isSelected('users')">
        {$this->partial('partial/_users')}
    </div>
</div>

{$this->angularTemplates('admin')}
