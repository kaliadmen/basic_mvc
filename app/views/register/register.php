<?php $this->start('head'); ?>

<?php $this->end(); ?>

<?php $this->start('body'); ?>
    <main class="my-form">
        <div class="cotainer">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Sign up</div>
                        <div class="card-body">
                            <form name="my-form" onsubmit="return validform()"  action="<?=PROJECTROOT?>register/register" method="post">
                                <div class="form-group row">
                                    <label for="first_name" class="col-md-4 col-form-label text-md-right">First Name</label>
                                    <div class="col-md-6">
                                        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" value="<?=$this->post['first_name'] ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="last_name" class="col-md-4 col-form-label text-md-right">Last Name</label>
                                    <div class="col-md-6">
                                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" value="<?=$this->post['last_name'] ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                                    <div class="col-md-6">
                                        <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="<?=$this->post['email'] ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="username" class="col-md-4 col-form-label text-md-right">Username</label>
                                    <div class="col-md-6">
                                        <input type="text" name="username" id="username" class="form-control" placeholder="username" value="<?=$this->post['username'] ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="passowrd" class="col-md-4 col-form-label text-md-right">Password</label>
                                    <div class="col-md-6">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="password">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                                    <div class="col-md-6">
                                        <input type="password" name="confirm" id="confirm" class="form-control" placeholder="confirm password">
                                    </div>
                                </div>

                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary mr-3">
                                        Register
                                    </button>

                                    Already have an account?<a href="<?=PROJECTROOT?>register/login">Sign In</a>
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