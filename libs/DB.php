<?php 

class DB{
    protected $db;
    function __construct()
    {
        $config = require '../config/db.php';
        try {
            $this->db = new PDO('mysql:host=' . $config['host'] . ';dbname=' .$config['name']. ';', $config['user'], $config['password'], array(
                PDO::ATTR_ERRMODE => TRUE
            ));
            // echo 'Success';

        } catch (\PDOException  $e) {
            echo $e->getMessage();
        }
    }
    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            foreach ($params as $key => $val) {
                if (is_int($val)) {
                    $type = PDO::PARAM_INT;
                } else {
                    $type = PDO::PARAM_STR;
                }
                $stmt->bindValue(':'.$key, $val, $type);
            }
        }
        $stmt->execute();
        return $stmt;
    }

    public function row($sql, $params = []) {
        $result = $this->query($sql, $params);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function column($sql, $params = []) {
        $result = $this->query($sql, $params);
        return $result->fetchColumn();
    }
    public function lastid(){
        $id = $this->db->lastInsertId();
        return $id;
    }
    public function lastOrgID(){
        $id = $this->row('SELECT LAST_INSERT_ID() FROM organizations');
        $id = $id['id'];
        return $id;
    }
    public static function encryptPassword($password)
	{
		return hash('sha256', $password);
	}
    function pure($str, $flags)
    {
        return trim(htmlentities(strip_tags($str), $flags, "UTF-8"));
    }

}

?>