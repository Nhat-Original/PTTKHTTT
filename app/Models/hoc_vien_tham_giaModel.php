<?php
namespace App\Models;

use CodeIgniter\Model;
use mysqli;
use Exception;
include 'DatabaseConnect.php';

class hoc_vien_tham_giaModel
{
    public $id_hoc_vien;
    public $id_lop_hoc;

    private $conn;
    function __construct(){}

    // function get_bainop_ById($studentId)
    // {
    //     $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    //     if ($this->conn->connect_error) {
    //         die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
    //     }

    //     $sql = "SELECT * FROM hoc_vien_tham_gia WHERE id_hoc_vien = $id_hoc_vien";
    //     $result = $this->conn->query($sql);

    //     if ($result->num_rows > 0) {
    //         $row = $result->fetch_assoc();
    //         $user = new chi_tiet_bai_nopModel();
    //         $this->id_hoc_vien = $row["id_hoc_vien"];
    //         $this->id_lop_hoc = $row["id_lop_hoc"];
    //         $this->conn->close();
    //         return $user;
    //     }
    //     else{
    //         $this->conn->close();
    //         return null;
    //     }
    // }

    function getAll_hoc_vien_tham_gia()
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $sql = "SELECT * FROM chi_tiet_bai_nop";
        $result = $this->conn->query($sql);
        $users = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new hoc_vien_tham_giaModel();
                $users[] = $user;
            }
        }
        $this->conn->close();
        return $users;
    }

    function queryDatabase($sql)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $result = $this->conn->query($sql);
        $rows = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        $this->conn->close();
        return $rows;
    }

    function inserthoc_vien_tham_gia($hoc_vien_tham_gia)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }


        $sql = "INSERT INTO hoc_vien_tham_gia (id_hoc_vien, id_lop_hoc) VALUES ('$hoc_vien_tham_gia->id_hoc_vien', '$hoc_vien_tham_gia->id_lop_hoc')";
        try {
            $this->conn->query($sql);
            $this->conn->close();
            return ['state' => true, 'message' => 'Cập nhật thành công'];
        } catch (Exception $e) {
            // Nếu có lỗi, xử lý lỗi
            $this->conn->close();
            return ['state' => false, 'message' => $e->getMessage()];
        }     
    }

    function deletehoc_vien_tham_gia($hoc_vien_tham_gia)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_lop_hoc = $hoc_vien_tham_gia->id_lop_hoc;
        $id_hoc_vien = $hoc_vien_tham_gia->id_hoc_vien;
        $sql = "DELETE FROM hoc_vien_tham_gia WHERE hoc_vien_tham_gia.id_hoc_vien = ? AND hoc_vien_tham_gia.id_lop_hoc = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_hoc_vien, $id_lop_hoc);

        try {
            $stmt->execute();
            $stmt->close();
            $this->conn->close();
            return ['state' => true, 'message' => 'Xóa thành công'];
        } catch (Exception $e) {
            $stmt->close();
            $this->conn->close();
            return ['state' => false, 'message' => $stmt->error];
        }     
    }

    function updatehoc_vien_tham_gia($user)
    {
        $this->conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
        if ($this->conn->connect_error) {
            die("Kết nối đến cơ sở dữ liệu thất bại: " . $this->conn->connect_error);
        }

        $id_hoc_vien = $this->conn->real_escape_string($user->id_hoc_vien);
        $id_lop_hoc = $this->conn->real_escape_string($user->id_lop_hoc);

        $sql = "UPDATE hoc_vien_tham_gia SET  id_lop_hoc = '$id_lop_hoc' WHERE id_hoc_vien = '$id_hoc_vien'";

        if ($this->conn->query($sql) === TRUE) {
            $this->conn->close();
            return ['state' => true, 'message' => ''];
        } else {
            $this->conn->close();
            return ['state' => false, 'message' => $this->conn->error];
        }
    } }