<div ng-controller="ComicsController">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <form class="navbar-form navbar-left">
                <div class="form-group">
                    <button class="btn btn-success" ng-click="openComicCreateDialog()">
                        {$this->translate('Create comics')}
                    </button>
                </div>
            </form>
        </div>
    </nav>


    <div class="row">
        <div class="col-md-3 comics">
            <div class="panel panel-default">
                <h2 class="panel-heading">{$this->translate('Comics')}</h2>
                <div class="panel-body" ng-include="'adminComicsList'"></div>
            </div>
        </div>
        <div class="col-md-3" ng-show="activated()" ng-include="'adminStripsList'">

        </div>
        <div class="col-md-6" ng-show="activated()">

        </div>
    </div>
</div>
