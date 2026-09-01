<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Designation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class EmployeesImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {

            $row_name = $row['name'] ?? null;
            $row_mobile_number = $row['mobile_number'] ?? null;
            $row_email = $row['email'] ?? null;
            $row_designation = $row['designation'] ?? null;
            $row_emp_code = $row['employee_code'] ?? null;
            $row_level = $row['level'] ?? null;
            $row_cell = $row['cell'] ?? null;
            $row_posted_at = $row['posted_at_hord_name'] ?? null;
            $row_pan_no = $row['pan_no'] ?? null;



            // Skip if name is empty
            if (empty($row_name)) {
                return null;
            }

            // Check if user already exists by emp_code, mobile_number, or email (if provided)
            $existingUser = User::where(function ($q) use ($row_emp_code, $row_email, $row_mobile_number) {
                if ($row_emp_code) {
                    $q->orWhere('emp_code', $row_emp_code);
                }
                if ($row_email) {
                    $q->orWhere('email', $row_email);
                }
                if ($row_mobile_number) {
                    $q->orWhere('mobile_number', $row_mobile_number);
                }
            })->first();

            if ($existingUser) {
                Log::warning('Employee already exists: ' . ($row_emp_code ?? $row_email ?? $row_mobile_number ?? 'unknown'));
                return null;
            }

            // Create user with default password and assign Employee role
            $user = User::create([
                'name' => $row_name,
                'email' => $row_email ?? $row_emp_code, // Use emp_code as email if email is not provided to ensure uniqueness
                'mobile_number' => $row_mobile_number ?? null,
                'password' => $row_pan_no,  // Use pan_no as password for initial login (Model handles hashing)
                'profile_image' => 'no-image.png',
                'designation_id' => $this->getDesignationId($row_designation ?? null),
                'emp_code' => $row_emp_code,
                'level' => $row_level,
                'cell' => $row_cell,
                'posted_at' => $row_posted_at,
                'pan_no' => $row_pan_no,
                'created_by' => auth()->id() ?? 1,
                'updated_by' => auth()->id() ?? 1,
            ]);

            // Assign Employee role
            if ($user) {
                $user->assignRole('EMPLOYEE');
            }
            return $user;
        } catch (\Exception $e) {
            Log::error('Error importing employee: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get designation ID from name
     */
    private function getDesignationId($designationName)
    {
        if (empty($designationName)) {
            return null;
        }

        $designation = Designation::where('title', $designationName)
            ->orWhere('title_hi', $designationName)
            ->first();

        return $designation ? $designation->id : null;
    }
}
