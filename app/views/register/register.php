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
                            <form name="my-form" onsubmit="return validform()"  action="" method="post">
                                <?=FormHelper::display_errors($this->display_errors)?>

                                <?=FormHelper::generate_csrf_input()?>

                                <?=FormHelper::build_input('text', 'first_name', 'First Name', $this->new_user->first_name, ['class' => 'form-group row'], ['class' => 'form-control input-sm']) ?>

                                <?=FormHelper::build_input('text', 'last_name', 'Last Name', $this->new_user->last_name, ['class' => 'form-group row'], ['class' => 'form-control input-sm']) ?>

                                <?=FormHelper::build_input('text', 'email', 'Email', $this->new_user->email, ['class' => 'form-group row'], ['class' => 'form-control input-sm']) ?>

                                <?=FormHelper::build_input('text', 'username', 'Username', $this->new_user->username, ['class' => 'form-group row'], ['class' => 'form-control input-sm']) ?>

                                <?=FormHelper::build_input('password', 'password', 'Password', $this->new_user->password, ['class' => 'form-group row'], ['class' => 'form-control input-sm']) ?>

                                <?=FormHelper::build_input('password', 'confirm', 'Confirm Password', '', ['class' => 'form-group row'], ['class' => 'form-control input-sm']) ?>

                                <div class="col-md-6 offset-md-4">
                                    <?=FormHelper::build_submit('Register', ['class' => 'btn btn-primary mr-3']) ?>

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