<?php
class Currency_m extends Model{

    // Get all from table currency
    public function getList(){
        $sql = "SELECT * FROM `currency`";
        return $this->db->query($sql);
    }

    // GetById
    public function getById($id){
        $id = (int)$id;
        $sql = "select * from `currency` where `id` = '{$id}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    // Save currency rate
    public function save($data, $id = null){
        if ( !isset($data['title']) || !isset($data['rate']) ){
            return false;
        }

        $id = (int)$id;
        $title = $this->db->escape($data['title']);
        $rate = $this->db->escape($data['rate']);

        if ( !$id ){ // Add new record
            $sql = "
                insert into `currency`
                   set title = '{$title}',
                       rate = '{$rate}'
            ";
        } else { // Update existing record
            $sql = "
                update `currency`
                   set title = '{$title}',
                       rate = '{$rate}'
                   where `id` = {$id}
            ";
        }

        return $this->db->query($sql);
    }

    // Delete
    public function delete($id){
        $id = (int)$id;
        $sql = "delete from `currency` where `id` = {$id}";
        return $this->db->query($sql);
    }

}