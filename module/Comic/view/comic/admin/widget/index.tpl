{foreach from=$comics item=comic}
    <div class="comic"></div>
{foreachelse}
    <div class="alert alert-info" role="alert">
        <p>{$this->translate("You don't have any comics yet.")}</p>
        <p><a ng-click="$emit('openComicCreateDialog')">{$this->translate("Create your first comic.")}</a></p>
    </div>
{/foreach}
