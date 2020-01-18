<?php

include_once "DAO.php";

class DAOUser extends DAO
{
    public function getUser($ID)
    {
        $stmt = cDB::getInstance()->prepare('SELECT * FROM `user` WHERE ID = ?');
        if (!$stmt) {
            Constants::Log($stmt->errorInfo());
            return false;
        }

        $stmt->bindValue(1, $ID);
        if (!$stmt->execute()) {
            return false;
        }

        return $stmt->fetchObject();
    }

    public function getUserByAsanaID($ID)
    {
        $stmt = cDB::getInstance()->prepare('SELECT au.user_id_in_asana, u.* FROM `asanaaccount_user` au JOIN `user` u ON u.ID = au.ID_user WHERE user_id_in_asana = ?');
        if (!$stmt) {
            Constants::Log(cDB::getInstance()->errorInfo());
            return false;
        }
        $stmt->bindValue(1, $ID);
        if (!$stmt->execute()) {
            return false;
        }

        return $stmt->fetchObject();
    }
}