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
        r.owner_id,
        r.name,
        r.address,
        r.description,
        r.image,
        r.contact,
        r.price,
        r.rating,
        r.summary,
        r.created_at,
        r.type,
        r.is_available
        FROM `motel_room` as r order by r.created_at desc';
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function getById(int $id)
    {
        $sql = 'SELECT 
        r.id,
        r.owner_id,
        r.name,
        r.address,
        r.description,
        r.image,
        r.contact,
        r.price,
        r.rating,
        r.summary,
        r.type,
        r.is_available
        FROM `motel_room` as r
        where r.id = ?;';
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute([$id]);
//        $stmt->debugDumpParams();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function fetchMotelRoomWithName(string $name, string $location)
    {
        $sql = "SELECT
        r.id,
        r.owner_id,
        r.name,
        r.address,
        r.description,
        r.image,
        r.contact,
        r.price,
        r.rating,
        r.created_at,
        r.type,
        r.is_available
        FROM motel_room AS r
        WHERE r.name like lower('%$name%')
        and r.address like lower('%$location%')
        order by r.created_at desc;";
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function getCommentOfRoom(int $id){
        $sql = "SELECT
        c2.fullname,
        c.content,
        c.created_at
        FROM motel_room AS r join comment c on r.id = c.motel_room_id
        join customer c2 on c.customer_id = c2.cid
        WHERE
        r.id = $id
        order by r.created_at desc;";
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute();
//        $stmt->debugDumpParams();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function insertMotelRoom(MotelRoom $motelRoom)
    {
        $sql = 'INSERT INTO `motel_room` (OWNER_ID, NAME, ADDRESS, SUMMARY, IMAGE, CONTACT, PRICE, RATING, DESCRIPTION, TYPE) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = DB::getInstance()->prepare($sql);
        $exec = $stmt->execute(
            array(
                $motelRoom->getOwnerId(),
                $motelRoom->getName(),
                $motelRoom->getAddress(),
                $motelRoom->getSummary(),
                $motelRoom->getImage(),
                $motelRoom->getContact(),
                $motelRoom->getPrice(),
                $motelRoom->getRating(),
                $motelRoom->getDescription(),
                $motelRoom->getType()
            )
        );
        $stmt->debugDumpParams();
        return $exec;
    }

    protected function insertComment(int $idRoom,int $cusId ,string $comment){
        $sql = 'INSERT INTO `comment` (MOTEL_ROOM_ID, CUSTOMER_ID, CONTENT) 
        VALUES (?, ?, ?)';
        $stmt = DB::getInstance()->prepare($sql);
        $exec = $stmt->execute(
            array(
                $idRoom,
                $cusId,
                $comment
            )
        );
//        $stmt->debugDumpParams();
        return $exec;
    }

    protected function addRenting($idCus,$ownerId, $idRoom, $startDateRent, $numberMonthRent){
        $sql = 'INSERT INTO `renting` (CUSTOMER_ID, OWNER_ID, MOTEL_ROOM_ID, RENTAL_START_DATE, TOTAL_MONTH_RENTAL, STATUS) 
        VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = DB::getInstance()->prepare($sql);
        $exec = $stmt->execute(
            array(
                $idCus,
                $ownerId,
                $idRoom,
                $startDateRent,
                $numberMonthRent,
                "pending"
            )
        );
        $sql = 'UPDATE `motel_room` r SET r.is_available = ?
        where r.id = ?';
        $stmt = DB::getInstance()->prepare($sql);
        $exec = $stmt->execute(
            array(
                '0',
                $idRoom
            )
        );
//        $stmt->debugDumpParams();
        return $exec;
    }

    protected function updateToDB(MotelRoom $motelRoom)
    {
        $sql = 'UPDATE `motel_room` r SET r.name = ?,
        r.address = ?,
        r.summary = ?,
        r.image = ?,
        r.contact = ?,
        r.price = ?,
        r.rating = ?,
        r.description = ?,
        r.type = ?
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
                $motelRoom->getType(),
                $motelRoom->getId()
            )
        );
//        $stmt->debugDumpParams();
        return $exec;
    }
}
