<?php

class Database
{
    private $db_connect;
    private $error = null;
    private $result;

    public function __construct($host, $login, $password, $db)
    {
        $this->db_connect = mysqli_connect($host, $login, $password, $db);
        mysqli_set_charset($this->db_connect, "utf8");

        if (!$this->db_connect) {
            $this->error = mysqli_connect_error();
        }
    }

    /**
     * Подготовленый запрос к базе данных с переданными параметрами
     * @param $sql - запрос к базе данных
     * @param array $data - массив параметров
     * @return bool
     */
    public function executeQuery($sql, $data = [])
    {
        $this->error = null;
        $stmt = db_get_prepare_stmt($this->db_connect, $sql, $data);

        if (mysqli_stmt_execute($stmt) && $r = mysqli_stmt_get_result($stmt)) {
            $this->result = $r;
            $res = true;
        } else {
            $this->error = mysqli_error($this->db_connect);
            $res = false;
        }

        return $res;
    }

    /**
     * Возвращает последнию ошибку
     * @return null|string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Возвращает массив элементов из базы
     * @return array|null
     */
    public function getResultArray()
    {
        return mysqli_fetch_all($this->result, MYSQLI_ASSOC);
    }

    /**
     * Возвращает ID
     * @return int|string
     */
    public function getID()
    {
        return mysqli_insert_id($this->db_connect);
    }

    /**
     * Возвращает число элементов
     * @return int
     */
    public function getNumRows()
    {
        return mysqli_num_rows($this->result);
    }

    /**
     * Возвращает очищенный текст от инъекций
     * @param $str - входной параметр
     * @return string
     */
    public function getEscapeStr($str)
    {
        return $this->db_connect->real_escape_string($str);
    }
}

?>