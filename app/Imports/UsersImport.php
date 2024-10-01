<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'name' => $row['name'], // กำหนดคอลัมน์ที่ตรงกับชื่อฟิลด์ใน Excel
            'email' => $row['email'],
            'password' => bcrypt($row['password']), // เข้ารหัสรหัสผ่าน
            'position' => $row['position'],
            'department' => $row['department'],  // แผนก
            'phone' => $row['phone'],          // เบอร์โทร
            'is_admin' => $row['is_admin'],    // เป็นแอดมินหรือไม่ (1 หรือ 0)
        ]);
    }
}
