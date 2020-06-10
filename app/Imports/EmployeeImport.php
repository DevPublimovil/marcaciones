<?php

namespace App\Imports;

use App\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Departament;
use App\Company;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Timetable;

class EmployeeImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Employee([
            'name_employee' => $row[0],
            'surname_employee' => $row[1],
            'cod_marking' => $row[2],
            'cod_terminal' => $row[3],
            'salary' => $row[4],
            'position' => $row[5],
            'status' => ($row[6] == 'activo') ? 1 : 0,
            'type_employee' => ($row[7] == 'administrativo') ? 1 : 2,
            'user_id' => ($row[8]) ? $this->newUser($row[0], $row[1], $row[8], $row[11], $row[7]) : null,
            'company_id' => $this->newCompany($row[9]),
            'departament_id' => $this->newDepartament($row[10]),
        ]);
    }
    public function newDepartament($depto)
    {
        $name = $depto;
        $dep = Departament::where('name',$depto)->first();
        $departament = [];
        if($dep)
        {
            $departament = $dep;
        }
        else{
            $departament = Departament::create([
                'name' => $name,
                'display_name' => ucfirst($name)
            ]);
        }
        
        return $departament->id;
    }

    public function newCompany($company)
    {
        $name = $company;
        $comp = Company::where('name',$company)->first();
        $newcompany = [];
        if($comp)
        {
            $newcompany = $comp;
        }
        else{
            $newcompany = Company::create([
                'name' => $name,
                'display_name' => strtoupper($name)
            ]);
        }
        
        return $newcompany->id;
    }

    public function newUser($firstname, $lastname, $mail, $pass, $type)
    {
        if($type == 'administrativo')
        {
            $user = User::where('email', $mail)->first();
            $newuser = [];
            if($user)
            {
                $newuser = $user;
            }
            else
            {
                $newuser = User::create([
                    'role_id' => 1,
                    'name' => $firstname . ' ' . $lastname,
                    'email' => $mail,
                    'password' => Hash::make($pass)
                ]);
            }

            return $newuser->id;
        }
        else
        {
            return;
        }
    }
}
