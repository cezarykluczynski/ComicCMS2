<div ng-controller="ComicsController">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <form class="navbar-form navbar-left">
                <div class="form-group">
                    <button
                        class="btn btn-success comics-create-open-dialog"
                        ng-click="openComicEditDialog()"
                        ng-disabled="strips.editing() || strips.loadingEntity"
                    >
                        {$this->translate('Create comics')}
                    </button>
                </div>
            </form>
        </div>
    </nav>


    <div class="row">
        <div
            class=" comics"
            {literal}ng-class="{'col-sm-3': !strips.editing(), 'col-sm-2': strips.editing()}"{/literal}
        >
            <div class="panel panel-default">
                <h2 class="panel-heading">{$this->translate('Comics')}</h2>
                <div class="panel-body" ng-include="'adminComicsList'"></div>
            </div>
        </div>
        <div
            ng-show="activated() && !comics.loading"
            {literal}ng-class="{'col-sm-3': !strips.editing(), 'col-sm-2': strips.editing()}"{/literal}
            ng-include="'adminStripsList'"
        >

        </div>
        <div
            {literal}ng-class="{'col-sm-6': !strips.editing(), 'col-sm-8': strips.editing()}"{/literal}
            ng-show="activated() && !comics.loading && strips.editing() && !strips.loadingEntity"
            ng-include="'adminStripEdit'"
        >
        </div>

        <div
            class="col-sm-6"
            ng-show="strips.loadingEntity"
        >
            <div class="panel panel-default">
                <h2 class="panel-heading">{$this->translate('Loading entity...')}</h2>
                <div class="panel-body" ng-include="'loading'"></div>
            </div>
        </div>
    </div>
</div>
