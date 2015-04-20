<div ng-app="admin" ng-controller="TabController as panel">
    <ul {literal}ng-init="tab = comic"{/literal} class="nav nav-tabs">
        <li {literal}ng-class="{active:panel.isSelected('comic') }"{/literal}>
            <a href ng-click="panel.selectTab('comic')">{$this->translate("Comics")}</a>
        </li>
        <li {literal}ng-class="{active:panel.isSelected('comments') }"{/literal}>
            <a href ng-click="panel.selectTab('comments')">{$this->translate("Comments")}</a>
        </li>
        <li {literal}ng-class="{active:panel.isSelected('users') }"{/literal}>
            <a href ng-click="panel.selectTab('users')">{$this->translate("Users")}</a>
        </li>
    </ul>

    <div ng-show="panel.isSelected('comic')" ng-app="admin-comic">
        Comics
    </div>
    <div ng-show="panel.isSelected('comments')" np=app="admin-comments">
        Comments
    </div>
    <div ng-show="panel.isSelected('users')" np=app="admin-users">
        Users
    </div>
</div>