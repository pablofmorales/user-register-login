<?php
namespace Models\Validators;

use Symfony\Component\Validator\Constraints as Assert;

class Authentication
{
    private $validator;
    private $users;
    private $errors = [];

    public function __construct($validator, $users = null)
    {
        $this->validator = $validator;
        $this->users = $users;
    }

    public function isValid($data)
    {
        $errors = $this->validator->validateValue(
            $data, $this->getValidationConstraints()
        );

        if (count($errors)) {
            foreach ($errors as $error) {
                $this->setError($error->getPropertyPath(), $error->getMessage());
            }
        } else {
            if ($this->users->emailExists($data['email'])) {
                return true;
            } else {
                $this->errors['email'] = 'Email already exists';
            }
        }

        return false;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function setError($key, $message)
    {
        $key = str_replace(["[", "]"], '', $key);
        $this->errors[$key] = $message;
    }

    private function getValidationConstraints()
    {
        return new Assert\Collection([
            'email' => [
                new Assert\NotBlank([
                    'message' => 'Email is required'
                ]), new Assert\Email([
                    'message' => 'Invalid Email'
                ]),
            ],
            'password' => [
                new Assert\Length([
                    'min' => 6,
                    'minMessage' => 'Password must have more than 5 characters'
                ]),
            ],
        ]);
    }
}
