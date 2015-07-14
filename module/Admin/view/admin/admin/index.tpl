<div ng-app="admin" ng-controller="TabController as panel" ng-cloak>
    {$this->partial('partial/_navbar')}
    <ul ng-init="tab = comics" class="nav nav-tabs root-tabs">
        <li class="tab dashboard" {literal}ng-class="{active:panel.isSelected('dashboard') }"{/literal}>
            <a href ng-click="panel.selectTab('dashboard')">{$this->translate("Dashboard")}</a>
        </li>
        <li class="tab comics" {literal}ng-class="{active:panel.isSelected('comics') }"{/literal}>
            <a href ng-click="panel.selectTab('comics')">{$this->translate("Comics")}</a>
        </li>
        <li class="tab users" {literal}ng-class="{active:panel.isSelected('users') }"{/literal}>
            <a href ng-click="panel.selectTab('users')">{$this->translate("Users")}</a>
        </li>
        <li class="tab settings" {literal}ng-class="{active:panel.isSelected('settings') }"{/literal}>
            <a href ng-click="panel.selectTab('settings')">{$this->translate("Settings")}</a>
        </li>
    </ul>

    <div ng-show="panel.isSelected('dashboard')">
        {$this->partial('partial/_dashboard')}
    </div>
    <div ng-show="panel.isSelected('comics')">
        {$this->partial('partial/_comics')}
    </div>
    <div ng-show="panel.isSelected('users')">
        {$this->partial('partial/_users')}
    </div>
    <div ng-show="panel.isSelected('settings')">
        Settings
    </div>
</div>

{$this->angularTemplates('admin')}
