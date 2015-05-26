<div ng-app="admin-signin" class="jumbotron admin-auth">
    <form method="post" name="signin" ng-submit="submit()" ng-controller="SigninController">
        <ng-messages for="signin">
            <ng-message ng-if="signin.error" class="alert alert-danger">
                {$this->translate("Authorization failed. Check your spelling.")}
            </ng-message>
            <ng-message ng-if="signin.success" class="alert alert-success">
                {$this->translate("Success! Redirecting...")}
            </ng-message>
        </ng-messages>

        <h2>{$this->translate("Sign in")}</h2>

        <fieldset ng-disabled="pending">
            <div class="form-group">
                <label for="email">{$this->translate("Email*:")}</label>
                <input
                    id="email"
                    name="email"
                    class="form-control"
                    type="email"
                    ng-model="user.email"
                    ng-required="true"
                    ng-minlength="6"
                    value="{$this->email}"
                    placeholder="{$this->translate('Enter email')}">
            </div>
            <div class="form-group">
                <label for="password">{$this->translate("Password*:")}</label>
                <input
                    id="password"
                    name="password"
                    class="form-control"
                    type="password"
                    ng-model="user.password"
                    ng-required="true"
                    value="{$this->password}"
                    placeholder="{$this->translate('Enter password')}">
            </div>
            <div class="checkbox remember">
                <label for="remember">
                    <input type="checkbox" id="remember">{$this->translate("Remember me")}
                </label>
            </div>
            <div class="form-group submit">
                <button type="submit" class="btn btn-default btn-lg btn-primary" ng-disabled="signin.$invalid">
                    {$this->translate("Submit")}
                </button>
            </div>
        </fieldset>
    </form>
</div>

