<?php

include_once "DAO.php";

class DAOTask extends DAO
{
    /**
     * Возвращает все неотправленые в TG таски до текущего времени +5 мин
     * @return bool|PDOStatement
     * @throws Exception
     */
    public function getTasksToNotifyAbout()
    {
        $stmt = cDB::getInstance()->prepare('
                SELECT
                	t.*,
                	u.ID_telegram,
                	`time_to_notify` - NOW() AS diffInSeconds 
                FROM
                	`task` t
                	JOIN `user` u ON t.ID_user = u.ID 
                WHERE
                	`sent` = 0
                	AND `time_to_notify` - NOW() < ' . Constants::get('CRON_PERIOD_IN_MINUTES') . ' * 60 
                ORDER BY
                	`time_to_notify` ASC');
        if ($stmt->execute()) {
            return $stmt;
        } else {
            return false;
        }
    }

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

        if ($taskObj->time_to_notify instanceof DateTime) {
            $timeStr = $taskObj->time_to_notify->format('Y-m-d\TH:i:s.u');
        } else {
            $timeStr = (string)$taskObj->time_to_notify;
        }

        $stmt->bindValue(':ID_user', $taskObj->ID_user);
        $stmt->bindValue(':ID_in_asana', $taskObj->ID_in_asana);
        $stmt->bindValue(':name', $taskObj->name);
        $stmt->bindValue(':description', $taskObj->description);
        $stmt->bindValue(':time_to_notify', $timeStr);
        $stmt->bindValue(':sent', $taskObj->sent ? 1 : 0);

        $ret = $stmt->execute();
        return $ret;
    }

    public function deleteTaskByAsanaID($taskAsanaID)
    {
        $stmt = cDB::getInstance()->prepare('DELETE FROM `task` WHERE `ID_in_asana` = ?');
        $stmt->bindValue(1, $taskAsanaID);
        return $stmt->execute();
    }


    /**
     * Переопределен для правильного распознавание IDE и подсказки методов
     * @return self
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

}