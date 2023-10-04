<?php

class MotelRoomHandler extends MotelRoomDAO
{
    private $executionFeedback;

    public function getExecutionFeedback()
    {
        return $this->executionFeedback;
    }

    public function setExecutionFeedback($executionFeedback)
    {
        $this->executionFeedback = $executionFeedback;
    }

    public function getAllMotelRoom()
    {
        if ($this->getAll()) {
            return $this->getAll();
        } else {
            return Util::DB_SERVER_ERROR;
        }
    }

    public function getMotelRoomByName(MotelRoom $motelRoom)
    {
        if ($this->fetchMotelRoomWithName($motelRoom->getId())) {
            $this->setExecutionFeedback(1);
            return $this->fetchMotelRoomWithName($motelRoom->getId());
        }
        return $this->setExecutionFeedback(0);
    }

}