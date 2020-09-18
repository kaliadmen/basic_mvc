<?php use Core\FormHelper; ?>
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
                                <?=FormHelper::display_errors($this->display_errors)?>

                                <?=FormHelper::generate_csrf_input()?>

                                <?=FormHelper::build_input('text', 'username', 'Username', $this->login->username, ['class' => 'form-group row'], ['class' => 'form-control input-sm']) ?>

                                <?=FormHelper::build_input('password', 'password', 'Password', $this->login->username, ['class' => 'form-group row'], ['class' => 'form-control input-sm']) ?>

                                <?=FormHelper::build_checkbox_block('Remember me', 'remember_me',$this->login->get_remember_me(),['class' => 'form-group row'], ['class' => 'checkbox col-md-6 offset-md-4']) ?>

                                <div class="col-md-6 offset-md-4">
                                    <?=FormHelper::build_submit('Register', ['class' => 'btn btn-primary mr-3']) ?>
                                    Don't have an account?<a href="<?=PROJECTROOT?>register/register">Sign Up</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php $this->end(); ?>