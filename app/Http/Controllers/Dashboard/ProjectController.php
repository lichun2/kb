<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Dashboard;

use Jitamin\Http\Controllers\Controller;
use Jitamin\Model\ProjectModel;

/**
 * Project Controller.
 */
class ProjectController extends Controller
{
    /**
     * Project overview.
     */
    
     public function index()
    {
        $user = $this->getUser();

        if ($this->userSession->isAdmin()) {
            $project_ids = $this->projectModel->getAllIds();
        } else {
            $project_ids = $this->projectPermissionModel->getProjectIds($this->userSession->getId());
        }

        $nb_projects = count($project_ids);

        $paginator = $this->paginator
            ->setUrl('Dashboard/ProjectController', 'index', ['pagination' => 'projects', 'user_id' => $user['id']])
            ->setMax(10)
            ->setOrder(ProjectModel::TABLE.'.id')
            ->setDirection('DESC')
            ->setQuery($this->projectModel->getQueryColumnStats($this->projectPermissionModel->getProjectIds($user['id'])))
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'projects');

        $this->response->html($this->helper->layout->dashboard('dashboard/project/index', [
            'paginator'   => $paginator,
            'nb_projects' => $nb_projects,
            'title'       => t('Dashboard'),
            'user'        => $user,
        ]));
    } 
     
    /*public function index()
    {
        $user = $this->getUser();

        $paginator = $this->paginator
            ->setUrl('Dashboard/ProjectController', 'index', ['pagination' => 'projects', 'user_id' => $user['id']])
            ->setMax(10)
            ->setOrder(ProjectModel::TABLE.'.id')
            ->setDirection('DESC')
            ->setQuery($this->projectModel->getQueryColumnStats($this->projectPermissionModel->getActiveProjectIds($user['id'])))
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'projects');

        $this->response->html($this->helper->layout->dashboard('dashboard/project/index', [
            'title'             => t('Dashboard'),
            'paginator'         => $paginator,
            'user'              => $user,
        ]));
    }
   */
    /**
     * Starred projects.
     */
    public function starred()
    {
        $user = $this->getUser();

        $paginator = $this->paginator
            ->setUrl('Dashboard/ProjectController', 'starred', ['pagination' => 'starred', 'user_id' => $user['id']])
            ->setMax(10)
            ->setOrder(ProjectModel::TABLE.'.name')
            ->setQuery($this->projectModel->getQueryColumnStats($this->projectStarModel->getProjectIds($user['id'])))
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'starred');

        $this->response->html($this->helper->layout->dashboard('dashboard/project/starred', [
            'title'             => t('Starred projects'),
            'paginator'         => $paginator,
            'user'              => $user,
        ]));
    }

     /**
     * Display Gantt chart for all projects.
     */
    public function gantt()
    {
        $project_ids = $this->projectPermissionModel->getActiveProjectIds($this->userSession->getId());
        $filter = $this->projectQuery
            ->withFilter(new ProjectTypeFilter(ProjectModel::TYPE_TEAM))
            ->withFilter(new ProjectStatusFilter(ProjectModel::ACTIVE))
            ->withFilter(new ProjectIdsFilter($project_ids));

        $filter->getQuery()->asc(ProjectModel::TABLE.'.start_date');

        $this->response->html($this->helper->layout->app('manage/gantt', [
            'projects' => $filter->format(new ProjectGanttFormatter($this->container)),
            'title'    => t('Manage').' &raquo; '.t('Projects Gantt chart'),
        ]));
    }
}
