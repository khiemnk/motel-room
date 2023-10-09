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
        r.rating,
        r.summary,
        r.created_at
        FROM `motel_room` as r order by r.created_at desc';
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
        r.rating,
        r.summary
        FROM `motel_room` as r
        where r.id = ?;';
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function fetchMotelRoomWithName(string $name, string $location)
    {
        $sql = "SELECT
        r.id,
        r.name,
        r.address,
        r.description,
        r.image,
        r.contact,
        r.price,
        r.rating,
        r.created_at
        FROM motel_room AS r
        WHERE r.name like lower('%$name%')
        and r.address like lower('%$location%')
        order by r.created_at desc;";
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function insertMotelRoom(MotelRoom $motelRoom){
        $sql = 'INSERT INTO `motel_room` (NAME, ADDRESS, SUMMARY, IMAGE, CONTACT, PRICE, RATING, DESCRIPTION) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = DB::getInstance()->prepare($sql);
        $exec = $stmt->execute(
            array(
                $motelRoom->getName(),
                $motelRoom->getAddress(),
                $motelRoom->getSummary(),
                $motelRoom->getImage(),
                $motelRoom->getContact(),
                $motelRoom->getPrice(),
                $motelRoom->getRating(),
                $motelRoom->getDescription()
            )
        );
//        $stmt->debugDumpParams();
        return $exec;
    }

    protected function updateToDB(MotelRoom $motelRoom){
        $sql = 'UPDATE `motel_room` r SET r.name = ?,
        r.address = ?,
        r.summary = ?,
        r.image = ?,
        r.contact = ?,
        r.price = ?,
        r.rating = ?,
        r.description = ?
        where r.id = ?';
        $stmt = DB::getInstance()->prepare($sql);
        $exec = $stmt->execute(
            array(
                $motelRoom->getName(),
                $motelRoom->getAddress(),
                $motelRoom->getSummary(),
                $motelRoom->getImage(),
                $motelRoom->getContact(),
                $motelRoom->getPrice(),
                $motelRoom->getRating(),
                $motelRoom->getDescription(),
                $motelRoom->getId()
            )
        );
//        $stmt->debugDumpParams();
        return $exec;
    }
}
