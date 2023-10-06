<?php

class MotelRoomDAO
{
    public function __construct()
    {
    }

    protected function getAll()
    {
        $sql = 'SELECT 
        r.id,
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

    protected function getById(int $id)
    {
        $sql = 'SELECT 
        r.id,
        r.name,
        r.address,
        r.description,
        r.image,
        r.contact,
        r.price,
        r.rating
        FROM `motel_room` as r
        where r.id = ?;';
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function fetchMotelRoomWithName(string $name)
    {
        $sql = "SELECT
        r.id,
        r.name,
        r.address,
        r.description,
        r.image,
        r.contact,
        r.price,
        r.rating
        FROM motel_room AS r
        WHERE r.name like '%$name%'";
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
