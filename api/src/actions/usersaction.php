<?php

namespace actions;

class UsersAction extends Action {
    private $offset = 0;
    private $limit = 5;

    public function __construct(?int $offset, ?int $limit) 
    {
        if ($offset>0) $this->offset = $offset;
        if ($limit>0) $this->limit = $limit;
    }

    public function run(\mysqli $db) : void
    {
        $json = new \models\ListStudent();

        $json->offset = $this->offset;
        $json->limit = $this->limit;

        $sql = "SELECT COUNT(*) FROM `students` WHERE 1";
        $res = $db->query($sql);
        list($all_student) = $res->fetch_row();
        $json->pages = ceil($all_student/$this->limit);
        $json->page = floor($this->offset/$this->limit)+1;

        $sql = "SELECT * FROM `students` WHERE 1 ORDER BY `id` ASC LIMIT {$this->offset},$this->limit";
        $res = $db->query($sql);
    
        while ($row = $res->fetch_assoc()) {
            if ($row['groupid'] == 0)  $row['groupname'] = 'Default group';
            $json->list[] = $row;
        }

        if (count($json->list)>0) {
            \http\Response::answer(200, $json);
        } else {
            throw new \Exception('No data', 204); 
        }
    }
}