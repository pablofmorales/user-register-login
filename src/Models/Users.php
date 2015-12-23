<?php
namespace Models;

class Users
{
    private $conn;

    const SALT = 'thisisasaltthisisasalt';
    const COST = 7;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function emailExists($email)
    {
        return (bool) $this->getByEmail($email);
    }

    public function save($data)
    {
        $today = new \DateTime('now');
        $data['created'] = $today->format('Y-m-d H:i:s');
        $data['password'] = $this->encrypt($data['password']);
        return $this->conn->insert('users', $data);
    }

    public function search($q)
    {
        $query = "SELECT name, email, created
            FROM users
            WHERE email like :q OR name like :q ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue("q", '%' . $q . '%');
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function getByEmail($email)
    {
        $query = 'SELECT * FROM users WHERE email=?';
        return $this->conn->fetchAssoc($query, [$email]);
    }

    public function checkCredentials($data)
    {
        $user = $this->getByEmail($data['email']);
        return \password_verify(
            $data['password'], $user['password']
        );
    }

    private function encrypt($plain)
    {
        $option = ["cost" => self::COST, "salt" => self::SALT];
        return \password_hash(
            $plain, PASSWORD_BCRYPT, $option
        );

    }
}
