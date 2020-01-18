<?php

include_once "DAO.php";

class DAOTask extends DAO
{
    public function setTask($taskObj)
    {
        $stmt = cDB::getInstance()->prepare('
            INSERT INTO `task`(`ID_user`, `ID_in_asana`, `name`, `description`, `time_to_notify`, `sent`)     
            VALUES (:ID_user, :ID_in_asana, :name, :description, :time_to_notify, :sent)
            ON DUPLICATE KEY UPDATE 
            `name` = VALUES(`name`),
            `description` = VALUES(`description`),
            `time_to_notify` = VALUES(`time_to_notify`),
            `sent` = VALUES(`sent`);
            ');

        $stmt->bindValue(':ID_user', $taskObj->ID_user);
        $stmt->bindValue(':ID_in_asana', $taskObj->ID_in_asana);
        $stmt->bindValue(':name', $taskObj->name);
        $stmt->bindValue(':description', $taskObj->description);
        $stmt->bindValue(':time_to_notify', $taskObj->time_to_notify->format('Y-m-d\TH:i:s.u'));
        $stmt->bindValue(':sent', $taskObj->sent ? 1 : 0);

        $ret = $stmt->execute();
        return $ret;
    }
}