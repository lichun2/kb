<?php 
$has_project_creation_access = $this->user->hasAccess('Project/ProjectController', 'create');
$has_task_creation_access = $this->user->hasAccess('Task/TaskSimpleController', 'create');
$is_private_project_enabled = $this->app->setting('disable_private_project', 0) == 0;
?>
<style type="text/css">
   
</style>
<div class="navbar navbar-default" role="navigation">
   <div class="navbar-header">
        <a href="/"><div class="logo"></div></a>
   </div>
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nb-collapse">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <h3>
            <span ><i class="fa fa-navicon"></i></span>
            <?php if (isset($page_title)): ?>
                <?= $this->text->e($page_title) ?>
            <?php elseif (isset($title)): ?>
                <?= $this->text->e($title) ?>
            <?php else: ?>
                ksyun
            <?php endif ?>
        </h3>
    </div>
    <!--
    <div class="navbar-header">
        <h3>
              <?php if ($has_project_creation_access || (!$has_project_creation_access && $is_private_project_enabled) || $has_task_creation_access): ?>
                    <a href="#" class="dropdown-menu has-sub"><i class="fa fa-plus-circle"></i><?= t('Create') ?></a>
                    <ul class="tabs">
                        <?php if ($has_project_creation_access): ?>
                            <li><i class="fa fa-cube"></i>
                                <?= $this->url->link(t('New project'), 'Project/ProjectController', 'create', [], false, 'popover small') ?>
                            </li>
                        <?php endif ?>
                        <?php if ($is_private_project_enabled): ?>
                            <li>
                                <i class="fa fa-lock"></i>
                                <?= $this->url->link(t('New private project'), 'Project/ProjectController', 'createPrivate', [], false, 'popover small') ?>
                            </li>
                        <?php endif ?>
                        <?php if ($has_task_creation_access): ?>
                        <div class="divider"></div>
                        <li>
                            <i class="fa fa-tasks"></i>
                            <?= $this->url->link(t('New task'), 'Task/TaskSimpleController', 'create', [], false, 'popover small') ?>
                        </li>
                        <?php endif ?>
                        <?= $this->hook->render('template:sidebar:creation-dropdown') ?>
                    </ul>
                <?php endif ?>
        </h3>
    </div>
    -->
    <div class="collapse navbar-collapse" id="nb-collapse">

        <ul class="nav navbar-nav navbar-right">
            <?php if ($has_project_creation_access || (!$has_project_creation_access && $is_private_project_enabled) || $has_task_creation_access): ?>
                <li>
                <a href="#" class="dropdown-menu has-sub"><i class="fa fa-plus-circle"></i></a>
                <ul class="tabs">
                    <?php if ($has_project_creation_access): ?>
                        <li><i class="fa fa-cube"></i>
                            <?= $this->url->link(t('New project'), 'Project/ProjectController', 'create', [], false, 'popover small') ?>
                        </li>
                    <?php endif ?>
                    <?php if ($is_private_project_enabled): ?>
                        <li>
                            <i class="fa fa-lock"></i>
                            <?= $this->url->link(t('New private project'), 'Project/ProjectController', 'createPrivate', [], false, 'popover small') ?>
                        </li>
                    <?php endif ?>
                    <?php if ($has_task_creation_access): ?>
                    <div class="divider"></div>
                    <li>
                        <i class="fa fa-tasks"></i>
                        <?= $this->url->link(t('New task'), 'Task/TaskSimpleController', 'create', [], false, 'popover small') ?>
                    </li>
                    <?php endif ?>
                    <?= $this->hook->render('template:sidebar:creation-dropdown') ?>
                </ul>
                </li>

            <?php endif ?>
            <li >
                <?= $this->url->link('<i class="fa fa-search"></i>'.t(''), 'SearchController', 'index') ?>
            </li>
            <li >
                <?php if ($this->user->hasNotifications()): ?>
                    <?= $this->url->link('<i class="fa fa-bell web-notification-icon"></i><br />'.t('Notice'), 'Dashboard/NotificationController', 'index', [], false, '', t('You have unread notifications')) ?>
                <?php else: ?>
                    <?= $this->url->link('<i class="fa fa-bell"></i><br />'.t(''), 'Dashboard/NotificationController', 'index', [], false, '', t('You have no unread notifications')) ?>
                <?php endif ?>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-menu"><?= $this->avatar->currentUserSmall('avatar-inline') ?><?= $this->text->e($this->user->getFullname()) ?> <i class="fa fa-caret-down"></i></a>
                <ul>
                    <li>
                        <i class="fa fa-vcard"></i>
                        <?= $this->url->link(t('My profile'), 'Profile/ProfileController', 'show', ['user_id' => $this->user->getId()]) ?>
                    </li>
                    <li>
                        <i class="fa fa-history"></i>
                        <?= $this->url->link(t('My history'), 'Profile/HistoryController', 'timesheet', ['user_id' => $this->user->getId()]) ?>
                    </li>
                    <li>
                        <i class="fa fa-life-ring"></i>
                        <?= $this->url->link(t('Documentation'), 'DocumentationController', 'show') ?>
                    </li>
                    <?= $this->hook->render('template:header:dropdown') ?>
                    <div class="divider"></div>
                    <li>
                        <i class="fa fa-edit"></i>
                        <?= $this->url->link(t('Edit profile'), 'Profile/ProfileController', 'edit', ['user_id' => $this->user->getId()]) ?>
                    </li>
                    <li >
                        <i class="fa fa-gear"></i>
                        <?= $this->url->link(t('Admin'), 'Admin/AdminController', 'index', [], false, '', t('Admin Control Panel')) ?>
                    </li>
                    <?php if (!DISABLE_LOGOUT): ?>
                        <li>
                            <i class="fa fa-sign-out"></i>
                            <?= $this->url->link(t('Logout'), 'Auth/AuthController', 'logout') ?>
                        </li>
                    <?php endif ?>
                </ul>
            </li>
        </ul>
        <?= $this->navbarSearch->render(isset($project) ? $project : []) ?>
    </div>
</div>
