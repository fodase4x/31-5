<?php

class BD
{

    const BD_HOST = "localhost";
    const BD_NOME = "db_aula_tai";
    const BD_PORT = 3306;
    const BD_USUARIO = "root";
    const BD_SENHA = "";
    const BD_CHARSET = "utf8";

    public function conn()
    {
        $conn = "mysql:host=$this->host;
            dbname=$this->dbname;port=$this->port";

        return new PDO(
            $conn,
            $this->usuario,
            $this->senha,
            [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $this->db_charset]
        );
    }
    public function select()
    {
        $conn = $this->conn();
        $st = $conn->prepare("SELECT * FROM usuario");
        $st->execute();

        return $st;
    }

    public function inserir($nome_tabela, $dados)
    {
        $conn = $this->conn();
        $sql = "INSERT INTO $nome_tabela (";

            $flag=0;
            foreach ($dados as $campo => $valor){
                if ($flag == 0){
                    $sql .= " $campo";
                }else {
                    $sql .= ", $campo";
                }
                $flag=1;
            }
            $sql .= ") value (";


            $flag=0;
            $arrayDados=[];
            foreach ($dados as  $valor){
                $sql .= ($flag == 0) ? " ?" : ", ?";
                /*if ($flag == 0){
                    $sql .= ", ?";
                }else {
                    $sql .= ", ?";
                }*/
                $flag=1;
                $arrayDados[]= $valor;
            }
            $sql .= ")";

        $st = $conn->prepare($sql);
        $st->execute($arrayDados);

        return $st;
    }

    public function update($nome_tabela, $dados)
    {
        $id = $dados["id"];
        $conn = $this->conn();
        $sql = "UPDATE $nome_tabela SET WHERE id = ?";

        $flag=0;
            foreach ($dados as $campo => $valor){
                if ($flag == 0){
                    $sql .= " $campo";
                }else {
                    $sql .= ", $campo";
                }
                $flag=1;
                $arrayDados[]= $valor;
            }
            $sql .= "WHERE id = $id";
        $st = $conn->prepare($sql);
        $st->execute($arrayDados);

        return $st;
    }

    public function remover($nome_tabela, $id)
    {
        $conn = $this->conn();
        $sql = "DELETE FROM $nome_tabela WHERE id = ?";

        $st = $conn->prepare($sql);
        $arrayDados = [$id];
        $st->execute($arrayDados);

        return $st;
    }

    public function buscar($nome_tabela, $id)
    {
        $conn = $this->conn();
        $sql = "SELECT * FROM $nome_tabela WHERE id = ?";

        $st = $conn->prepare($sql);
        $arrayDados = [$id];
        $st->execute($arrayDados);

        return $st->fetchObject();
    }

    public function pesquisar($nome_tabela, $dados)
    {
        $conn = $this->conn();
        $sql = "SELECT * FROM $nome_tabela WHERE nome LIKE ?;";

        $st = $conn->prepare($sql);
        $arrayDados = ["%" . $dados["valor"] . "%"];
        $st->execute($arrayDados);

        return $st;
    }
}
