<?php $this->start('head'); ?>

<?php $this->end(); ?>

<?php $this->start('body'); ?>
    <main class="login-form mt-5">
        <div class="cotainer">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Sign In</div>
                        <div class="card-body">
                            <form action="<?=PROJECTROOT?>register/login" method="post">
                                <div class="bg-danger"><?=$this->display_errors ?></div>
                                <div class="form-group row">
                                    <label for="usernames" class="col-md-4 col-form-label text-md-right">Username</label>
                                    <div class="col-md-6">
                                        <input type="text" name="username" id="username" class="form-control" placeholder="username" required autofocus>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                    <div class="col-md-6">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="password" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="remember_me" name="remember_me" value="true">Remember Me
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary mr-3">
                                        Sign in
                                    </button>
                                    Don't have an account?<a href="<?=PROJECTROOT?>register/register">Sign Up</a>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>

    </main>
<?php $this->end(); ?>