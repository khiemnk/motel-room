<?php

class CustomerDAO
{

    public function __construct()
    {
    }

    protected function getByEmail($email)
    {
        $sql = 'SELECT * FROM `customer` WHERE email=?';
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, "Customer");
    }

    protected function getByCid($cid)
    {
        $sql = 'SELECT * FROM `customer` WHERE `customer`.`cid`=?';
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute([$cid]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, "Customer");
    }

    protected function getAll(int $cusId)
    {
        $sql = "SELECT
        c.cid,
        c.fullname,
        c.phone,
        c.email,
        r.name,
        m.motel_room_id,
        m.rental_start_date,
        m.total_month_rental,
        m.created_at
        FROM `customer` c 
        join `renting` m on c.cid = m.owner_id 
        join `motel_room` r on m.motel_room_id = r.id
        where c.cid = $cusId
        order by m.created_at desc;";
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute();
//        $stmt->debugDumpParams();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function getMyRenting(int $cusId)
    {
        $sql = "SELECT
        c.cid,
        c.fullname,
        c.phone,
        c.email,
        r.name,
        m.motel_room_id,
        m.rental_start_date,
        m.total_month_rental,
        m.created_at
        FROM `customer` c 
        join `renting` m on c.cid = m.customer_id 
        join `motel_room` r on m.motel_room_id = r.id
        where c.cid = $cusId
        order by m.created_at desc;";
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute();
//        $stmt->debugDumpParams();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function insert(Customer $customer)
    {
        $sql = 'INSERT INTO `customer` (`fullname`, `email`, `password`, `phone`) VALUES (?, ?, ?, ?)';
        $stmt = DB::getInstance()->prepare($sql);
        $exec = $stmt->execute(
            array(
            $customer->getFullName(),
            $customer->getEmail(),
            $customer->getPassword(),
            $customer->getPhone()
            )
        );
        return $exec;
    }

    protected function update(Customer $customer)
    {
        $query = "UPDATE customer SET fullname = :fullname, password = :password, phone = :phone WHERE cid = :cid;";
        $stmt = DB::getInstance()->prepare($query);
        $exec = $stmt->execute([
            'fullname'  => $customer->getFullName(),
            'password'  => $customer->getPassword(),
            'phone'     => $customer->getPhone(),
            'cid'       => $customer->getId()
        ]);
        return $exec;
    }

    protected function delete(Customer $customer)
    {
        $sql = 'DELETE FROM `customer` WHERE `customer`.`cid` = ?';
        $stmt = DB::getInstance()->prepare($sql);
        $exec = $stmt->execute([$customer->getId()]);
        return $exec;
    }

    /**
     * @param $email
     * @return string 1 if exists, otherwise it returns 0
     */
    protected function isCustomerExists($email) {
        $sql = 'SELECT COUNT(email) AS isEmailExist FROM customer WHERE email = ?';
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute([$email]);
        return  (int) $stmt->fetchColumn();
    }

    /**
     * @param $email
     * @return int: 1 if admin email, otherwise 0 (regular customer)
     */
    protected function isAdminCount($email) {
        $sql = 'SELECT COUNT(cid) FROM customer WHERE email = ? AND isadmin = 1';
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute([$email]);
        return  (int) $stmt->fetchColumn();
    }

    protected function updateToDB(Customer $customer) : bool
    {
        $sql = 'UPDATE `customer` c SET c.fullname = ?,
        c.address = ?,
        c.phone = ?,
        c.email = ?,
        c.avatar = ?
        where c.cid = ?';
        $stmt = DB::getInstance()->prepare($sql);
        $exec = $stmt->execute(
            array(
                $customer->getFullName(),
                $customer->getAddress(),
                $customer->getPhone(),
                $customer->getEmail(),
                $customer->getAvatar(),
                $customer->getId()
            )
        );
        $stmt->debugDumpParams();
        return $exec;
    }
}
