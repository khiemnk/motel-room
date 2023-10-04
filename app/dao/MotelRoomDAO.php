<?php

class MotelRoomDAO
{
    public function __construct()
    {
    }

    protected function getAll()
    {
        $sql = 'SELECT 
        r.name,
        r.address,
        r.description,
        r.image,
        r.contact,
        r.price,
        r.rating
        FROM `motel_room` as r';
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function fetchMotelRoomWithName($name)
    {
        $sql = 'SELECT
        r.name,
        r.address,
        r.description,
        r.image,
        r.contact
        FROM motel_room AS r
        WHERE r.name = ?;';
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute([$name]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}