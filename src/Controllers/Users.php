<?php
namespace Controllers;

use Symfony\Component\HttpFoundation\Request;

class Users
{
    private $view;
    private $validator;
    private $user;

    public function __construct($view, $validator, $user)
    {
        $this->view = $view;
        $this->validator = $validator;
        $this->user = $user;
    }

    public function create(Request $request)
    {
        $data = $request->request->all();
        if (count($data)) {
            if ($this->validator->isValid($data)) {
                $this->user->save($data);
                $data['success'] = true;
            } else {
                $data['errors'] = $this->validator->getErrors();
            }
        }

        return $this->view->render('users/signup.twig', $data);
    }

    public function search(Request $request)
    {
        $data = [];
        $q = $request->query->get('q');
        if ($q) {
            $data['users'] = $this->user->search($q);
        }
        return $this->view->render('users/search.twig', $data);
    }
}
