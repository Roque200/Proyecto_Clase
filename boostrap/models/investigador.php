<?php
require_once "sistem.php";
class Investigador extends Sistema{
    function create($date){
        return $rows_affected;
    }

    function read(){
        $this -> connect();
        $sth = $this -> _BD -> prepare("select i.intituto, inv.*
        from institucion i join investigador inv
        on i.id_institucion = inv.id_istitucion;");
        $sth -> execute();
        $data = $sth -> fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }

    function readOne(){
        return $data;
    }

    function update($data, $id){
        return $rows_affected;
    }

    function delete($id){
        return $rows_affected;
    }
}
?>