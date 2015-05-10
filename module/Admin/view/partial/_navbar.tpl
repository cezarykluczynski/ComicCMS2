<div class="user">
    <div class="dropdown user-actions">
        <button class="btn btn-default dropdown-toggle" type="button" id="user-actions" data-toggle="dropdown" aria-expanded="true">
            {$this->authenticatedUser->email}
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="user-actions">
            <li role="presentation"><a role="menuitem" tabindex="-1" href="{$this->url('admin-signout')}">
                {$this->translate('Sign out')}
            </a></li>
        </ul>
    </div>
</div>