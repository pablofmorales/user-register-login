<?php
namespace Controllers;

use Symfony\Component\HttpFoundation\Request;

class Authentication
{
    private $view;
    private $validator;
    private $user;
    private $session;

    public function __construct($view, $validator, $user, $session)
    {
        $this->view = $view;
        $this->validator = $validator;
        $this->user = $user;
        $this->session = $session;
    }

    public function login(Request $request)
    {
        $data = $request->request->all();
        if (count($data)) {
            if ($this->validator->isValid($data)) {
                if ($this->user->checkCredentials($data)) {
                   $this->session->set('email', $data['email']);
                    $data['success'] = true;
                } else {
                    $data['errors'][] = 'Invalid Credentials';
                }
            } else {
                $data['errors'] = $this->validator->getErrors();
            }
        }

        return $this->view->render('users/login.twig', $data);
    }

}
