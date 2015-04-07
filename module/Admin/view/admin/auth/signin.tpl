<div ng-app="signIn" class="jumbotron admin-auth">
    <h2>{$this->translate("Sign in")}</h2>
    <form method="post">
        <div class="form-group">
          <label for="email">{$this->translate("Email*:")}</label>
          <input type="email" class="form-control" id="email" placeholder="{$this->translate('Enter email')}">
        </div>
        <div class="form-group">
            <label for="password">{$this->translate("Password*:")}</label>
            <input type="password" class="form-control" id="password" placeholder="{$this->translate('Enter password')}">
        </div>
        <div class="checkbox remember">
            <label for="remember">
                <input type="checkbox" id="remember">{$this->translate("Remember me")}
            </label>
        </div>
        <div class="form-group submit">
            <button type="submit" class="btn btn-default btn-lg btn-primary">{$this->translate("Submit")}</button>
        </div>
    </form>
</div>
