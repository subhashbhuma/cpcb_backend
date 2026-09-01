<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DirectionIssuedTosSeeder extends Seeder
{
    public function run(): void
    {
         DB::table('direction_issued_tos')->truncate();
        /*
        |--------------------------------------------------------------------------
        | Act Type ID = 1
        |--------------------------------------------------------------------------
        */
        $issuedToActType1 = [
            "Industry",
            "17 Category of Industry",
            "GPI Category of Industry",
            "Authorities",
            "Urban Local / Municipal Bodies",
            "Common to all",
            "Others",

            // SPCBs / PCCs
            "Andhra Pradesh PCB",
            "Arunachal Pradesh SPCB",
            "Assam PCB",
            "Bihar SPCB",
            "Chhattisgarh Envt. Conv. Board",
            "Gujarat PCB",
            "Goa SPCB",
            "Haryana SPCB",
            "Himachal Pradesh SPCB",
            "Jharkhand SPCB",
            "Jammu & Kashmir SPCB",
            "Karnataka SPCB",
            "Kerala SPCB",
            "Maharashtra PCB",
            "Manipur PCB",
            "Madhya Pradesh PCB",
            "Meghalaya SPCB",
            "Mizoram PCB",
            "Nagaland PCB",
            "Odisha SPCB",
            "Punjab SPCB",
            "Rajasthan SPCB",
            "Sikkim SPCB",
            "Tamil Nadu PCB",
            "Telangana SPCB",
            "Tripura SPCB",
            "Uttar Pradesh PCB",
            "Uttarakhand PCB",
            "West Bengal PCB",

            // PCCs
            "Andaman & Nicobar PCC",
            "Chandigarh PCC",
            "Delhi PCC",
            "Daman & Diu and Dadra & Nagar Haveli PCC",
            "Lakshadweep PCC",
            "Puducherry PCC",

            // Collective
            "All SPCBs",
            "All PCCs",
            "All SPCBs/PCCs",
            "Ganga Basin SPCBs",

            // Authorities
            "Delhi Traffic Police",
            "NHAI",
            "DMRC",
            "DDA",
            "Delhi Transport Deptt.",
            "PWD",
            "CPWD",
            "New Delhi Municipal Corporation",
            "North Delhi Municipal Corporation",
            "East Delhi Municipal Corporation",
            "South Delhi Municipal Corporation",
            "Municipal Corporation Gurugram",
            "Municipal Corporation Ghaziabad",
            "Delhi Cantonment Board",
            "Northern Railway HQ",
            "DSIIDC",
            "Delhi University",

            // Departments
            "Additional/Special/Principal/Commissioner/Chief Secretary, Environment",
            "Irrigation & Flood Control Deptt.",
            "Deptt. of Forests, Ecology, Environment and Wildlife",
            "Principal Secretary Environment, UP Govt.",
        ];

        /*
        |--------------------------------------------------------------------------
        | Act Type ID = 2
        |--------------------------------------------------------------------------
        */
        $issuedToActType2 = [
            "Andhra Pradesh PCB",
            "Arunachal Pradesh SPCB",
            "Assam PCB",
            "Bihar SPCB",
            "Chhattisgarh Envt. Conv. Board",
            "Gujarat PCB",
            "Goa SPCB",
            "Haryana SPCB",
            "Himachal Pradesh SPCB",
            "Jharkhand SPCB",
            "Jammu & Kashmir SPCB",
            "Karnataka SPCB",
            "Kerala SPCB",
            "Maharashtra PCB",
            "Manipur PCB",
            "Madhya Pradesh PCB",
            "Meghalaya SPCB",
            "Mizoram PCB",
            "Nagaland PCB",
            "Odisha SPCB",
            "Punjab SPCB",
            "Rajasthan SPCB",
            "Sikkim SPCB",
            "Tamil Nadu PCB",
            "Telangana SPCB",
            "Tripura SPCB",
            "Uttar Pradesh PCB",
            "Uttarakhand PCB",
            "West Bengal PCB",

            "Chandigarh PCC",
            "Delhi PCC",
            "Daman & Diu and Dadra & Nagar Haveli PCC",
            "Lakshadweep PCC",
            "Puducherry PCC",

            "All SPCBs",
            "All PCCs",
            "All SPCBs/PCCs",
            "Ganga Basin SPCBs",
            "Others",

            // Special authorities
            "Delhi Traffic Police",
            "NHAI",
            "DMRC",
            "DDA",
            "Delhi Transport Deptt.",
            "PWD",
            "CPWD",
            "New Delhi Municipal Corporation",
            "North Delhi Municipal Corporation",
            "East Delhi Municipal Corporation",
            "South Delhi Municipal Corporation",
            "Municipal Corporation Gurugram",
            "Municipal Corporation Ghaziabad",
            "Delhi Cantonment Board",
            "Northern Railway HQ",
            "DSIIDC",
            "Delhi University",
        ];

        /*
        |--------------------------------------------------------------------------
        | Insert Logic (Reusable)
        |--------------------------------------------------------------------------
        */
        $this->seedByActType(1, $issuedToActType1);
        $this->seedByActType(2, $issuedToActType2);

        $this->command->info("\nAll Direction Issued To data seeded successfully.");
    }

    /**
     * Seed data by act type
     */
    private function seedByActType(int $actTypeId, array $titles): void
    {
        $this->command->info("\nSeeding Act Type ID = {$actTypeId}");

        foreach ($titles as $title) {
            DB::table('direction_issued_tos')->updateOrInsert(
                [
                    'title' => $title,
                    'direction_act_type_id' => $actTypeId,
                ],
                [
                    'title_hi'     => $title,
                    'is_approved'  => 1,
                    'is_published' => 1,
                    'created_by'   => 1,
                    'updated_by'   => 1,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]
            );

            $this->command->getOutput()->write('.');
        }
    }
}
