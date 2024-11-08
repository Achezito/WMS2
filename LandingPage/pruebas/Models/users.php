<?php
class User {
    private $id_user;
    private $user_name; 
    private $user_password;

    // Getter para id_user
    public function getIdUser() {
        return $this->id_user;
    }

    // Setter para id_user
    public function setIdUser($id_user) {
        $this->id_user = $id_user;
    }

    // Getter para user_name
    public function getUserName() {
        return $this->user_name;
    }

    // Setter para user_name
    public function setUserName($user_name) {
        $this->user_name = $user_name;
    }

    // Getter para user_password
    public function getUserPassword() {
        return $this->user_password;
    }

    // Setter para user_password
    public function setUserPassword($user_password) {
        $this->user_password = $user_password;
    }
}



?>