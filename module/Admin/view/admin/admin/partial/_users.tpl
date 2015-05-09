<div ng-app="admin-users" ng-controller="DashboardController as dashboard">
    <div class="row">
        <div class="widget">
            <div class="col-md-4">
                <div class="well">
                    <h2>{$this->translate('Users')}</h2>
                    <table class="items" ng-class="users.state">
                        <tr dir-paginate="user in users.users | itemsPerPage: users.perPage"
                            total-items="users.total" current-page="users.current">
                            <td>{literal}{{user.email}}{/literal}</td>
                        </tr>
                    </table>

                    <dir-pagination-controls on-page-change="users.pageChanged(newPageNumber)">
                    </dir-pagination-controls>
                </div>
            </div>
        </div>
    </div>
</div>