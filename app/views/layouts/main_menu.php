<?php
    $menu = Router::get_menu('menu_acl');
    $is_active = '';
    $current_page = current_page();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
        <div class="container">
            <a class="navbar-brand" href="<?=PROJECTROOT?>"><?=SITE_TITLE?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_menu" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="main_menu">
                <ul class="navbar-nav ml-auto">
                    <?php foreach($menu as $key => $value): ?>
                        <?php if(is_array($value)): ?>
                            <li class="nav-item dropdown">
                                <a class="dropdown-toggle nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?=$key?><span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu bg-dark">
                                    <?php foreach($value as $k => $val):?>
                                        <?php $is_active = ($val == $current_page) ? 'active' : '' ; ?>
                                        <?php if($k == 'separator'): ?>
                                            <li class="dropdown-divider" role="separator"></li>
                                        <?php else: ?>
                                            <li class="nav-item <?=$is_active?>">
                                                <a class="nav-link dropdown-item bg-dark" href="<?=$val?>"><?=$k?></a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php else: ?>
                            <?php $is_active = ($value == $current_page) ? 'active' : '' ; ?>
                            <li class="nav-item <?=$is_active?>">
                                <a class="nav-link" href="<?=$value?>"><?=$key?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                        <ul class="pull-right">
                            <?php if(current_user()->id): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Hello <?=current_user()->first_name?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                </ul>
            </div>
        </div>
    </nav>