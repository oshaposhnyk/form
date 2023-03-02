<?php

namespace app\controllers;

use core\Application;
use core\Controller;
use core\Request;
use app\models\User;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $registerModel = new User();
        if ($request->getMethod() === 'post') {
            $registerModel->loadData($request->getBody());
            if ($registerModel->validate() && $registerModel->save()) {
                if ($request->isAjax()) {
                    echo json_encode(['status' => 'ok', 'message' => 'Thanks for registering']); die;
                } else {
                    Application::$app->session->setFlash('success', 'Thanks for registering');
                }
                return 'Show success page';
            } else {
                if ($request->isAjax()) {
                    echo json_encode(['status' => 'error', 'errors' => $registerModel->errors]); die;
                }
            }


        }
        return $this->render('register', [
            'model' => $registerModel
        ]);
    }

}