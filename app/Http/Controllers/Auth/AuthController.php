<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Http\Controllers\Auth;

use Jitamin\Http\Controllers\Controller;

/**
 * Authentication Controller.
 */
class AuthController extends Controller
{
    /**
     * Display the form login.
     *
     * @param array $values
     * @param array $errors
     */
    public function login(array $values = [], array $errors = [])
    {
        if ($this->userSession->isLogged()) {
            $this->response->redirect($this->helper->url->to('Dashboard/DashboardController', 'index'));
        } else {
            $this->response->html($this->helper->layout->app('auth/login', [
                'captcha'   => !empty($values['username']) && $this->userLockingModel->hasCaptcha($values['username']),
                'errors'    => $errors,
                'values'    => $values,
                'no_layout' => true,
                'title'     => t('Login'),
            ]));
        }
    }

    /* public function login(array $values = array(), array $errors = array())
    {
        if ($this->userSession->isLogged()) {
            $this->response->redirect($this->helper->url->to('DashboardController', 'show'));
        } else {
            $ticket = $this->sessionStorage->ticket;
            if (!$ticket) {
                header("Location:" . SSO_SERVER_URL . "/login?service=" . SSO_SELF_SERVICE);
            } else {
                $url = SSO_SERVER_URL . "/validate?ticket=".$ticket."&service=" . SSO_SELF_SERVICE;
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_HEADER,0);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                curl_setopt($curl, CURLOPT_BINARYTRANSFER, true);
                $response = curl_exec($curl);
                $result = json_decode($response, true);
                if ($result['status'] === 'success') {
                    unset($this->sessionStorage->ticket); 
                    if (isset($result['userEmail'])) {
                        $user = substr($result['userEmail'], 0, strpos($result['userEmail'], '@kingsoft.com'));
                        list($valid, $errors) = $this->authValidator->validateForm(['username' => $user, 'password' => '']);
                        if ($valid) {
                            $this->response->redirect($this->helper->url->to('DashboardController', 'show'));
                        } else {
                            echo $errors['login'];
                        }
                    }
                } else {
                    echo "SSO登陆失败，请重试";
                }
            }
        }
    } */
   /* public function login(array $values = array(), array $errors = array())
    {
        if ($this->userSession->isLogged()) {
            $this->response->redirect($this->helper->url->to('DashboardController', 'show'));
        } else {
            $result = array();
            $result['status'] = "success";
            $result['userEmail'] = "admin@admin.com";
           // $result = json_decode($response, true);
            if ($result['status'] === 'success') {
                unset($this->sessionStorage->ticket); 
                if (isset($result['userEmail'])) {
                    $user = substr($result['userEmail'], 0, strpos($result['userEmail'], '@kingsoft.com'));
                    list($valid, $errors) = $this->authValidator->validateForm(['username' => $user, 'password' => '']);
                    if ($valid) {
                        $this->response->redirect($this->helper->url->to('DashboardController', 'show'));
                    } else {
                        echo $errors['login'];
                    }
                }
            } else {
                echo "SSO登陆失败，请重试";
            }
        }
        $this->response->redirect($this->helper->url->to('DashboardController', 'show'));
    }*/

    /**
     * Check credentials.
     */
    public function check()
    {
        $values = $this->request->getValues();
        $this->sessionStorage->hasRememberMe = !empty($values['remember_me']);
        list($valid, $errors) = $this->authValidator->validateForm($values);

        if ($valid) {
            $this->redirectAfterLogin();
        } else {
            $this->login($values, $errors);
        }
    }

    /**
     * Logout and destroy session.
     */
    /*public function logout()
    {
        if (!DISABLE_LOGOUT) {
            $this->sessionManager->close();
            $this->response->redirect($this->helper->url->to('Auth/AuthController', 'login'));
        } else {
            $this->response->redirect($this->helper->url->to('Dashboard/DashboardController', 'index'));
        }
    }*/

     public function logout()
    {
        $this->sessionManager->close();
        header("Location:" . SSO_SERVER_URL . "/logout?service=" . SSO_SELF_SERVICE);
    }
    /**
     * Redirect the user after the authentication.
     */
    protected function redirectAfterLogin()
    {
        if (isset($this->sessionStorage->redirectAfterLogin) && !empty($this->sessionStorage->redirectAfterLogin) && !filter_var($this->sessionStorage->redirectAfterLogin, FILTER_VALIDATE_URL)) {
            $redirect = $this->sessionStorage->redirectAfterLogin;
            unset($this->sessionStorage->redirectAfterLogin);
            $this->response->redirect($redirect);
        } else {
            $this->response->redirect($this->helper->url->to('Dashboard/DashboardController', 'index'));
        }
    }
}
