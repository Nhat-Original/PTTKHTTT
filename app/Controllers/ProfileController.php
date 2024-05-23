<?php

namespace App\Controllers;
use App\Models\UserModel;
use App\Models\GiangVienModel;
use App\Models\HocVienModel;
use App\Models\phan_cong_giang_vienModel;
use App\Models\hoc_vien_tham_giaModel;
use DateTime;

class ProfileController extends BaseController
{
    public function index($roleName = 'lecturer')
    {
        $data = [];
        $model = new UserModel();
        $navbar_data = array();

        if (session()->get('role') == 1) { // Admin
            $result = $model->executeCustomQuery(
                'SELECT ad.ho_ten, users.anh_dai_dien
                FROM users
                INNER JOIN ad ON users.id_ad = ad.id_ad
                WHERE users.id_user = ' . session()->get("id_user")
            );
            $navbar_data['username'] = "{$result[0]['ho_ten']}";
            $navbar_data['role'] = 'Adminstrator';
            $navbar_data['avatar_data'] = "{$result[0]['anh_dai_dien']}";
        } else if (session()->get('role') == 2) { // Lecturer
            $result = $model->executeCustomQuery(
                'SELECT gv.ho_ten, users.anh_dai_dien
                FROM users
                INNER JOIN giang_vien gv ON users.id_giang_vien = gv.id_giang_vien
                WHERE users.id_user = ' . session()->get("id_user")
            );
            $navbar_data['username'] = "{$result[0]['ho_ten']}";
            $navbar_data['role'] = 'Giảng viên';
            $navbar_data['avatar_data'] = "{$result[0]['anh_dai_dien']}";
        } else if (session()->get('role') == 3) { // Student
            $result = $model->executeCustomQuery(
                'SELECT hv.ho_ten, users.anh_dai_dien
                FROM users
                INNER JOIN hoc_vien hv ON users.id_hoc_vien = hv.id_hoc_vien
                WHERE users.id_user = ' . session()->get("id_user")
            );
            $navbar_data['username'] = "{$result[0]['ho_ten']}";
            $navbar_data['role'] = 'Học viên';
            $navbar_data['avatar_data'] = "{$result[0]['anh_dai_dien']}";
        }

        $data['navbar'] = view('Admin\ViewCell\NavBar', $navbar_data);

        if ($roleName == "lecturer") {
            // giang vien
            $teacherID = $_GET['id'];
            $lecturersModel = new GiangVienModel(); 
            $phancong = new phan_cong_giang_vienModel();
            
            $data['id'] = $teacherID;
            $data['user'] = $lecturersModel->getGiangVienById($teacherID);
            $data['attend'] = $phancong->getPhanCongByIDGiangVien($teacherID);
            $data['role_name'] = "Giảng viên";
            
            return view('ProfilePage', $data);
        } else {
            // hoc vien
            $id_hoc_vien = $_GET['id'];
            $hocVienModel = new HocVienModel();
            $thamgia = new hoc_vien_tham_giaModel();

            $data['user'] = $hocVienModel->getHocVienById($id_hoc_vien);
            $data['id'] = $id_hoc_vien;
            $data['attend'] = $thamgia->getThamGiaByIDHocVien($id_hoc_vien);       
            $data['role_name'] = "Học viên";

            return view('ProfilePage', $data);
        }
    }
}