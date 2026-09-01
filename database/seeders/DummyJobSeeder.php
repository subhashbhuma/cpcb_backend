<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DummyJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing data to start fresh
        // For PostgreSQL, we can use Cascade in the truncate statement
        DB::table('recruitment_announcements')->delete();
        DB::table('job_posts')->delete();
        DB::table('jobs')->delete();

        $jobs = [
            [
                'title' => 'Recruitment to the post of Scientist \'B\' and Assistant Law Officer',
                'title_hi' => 'वैज्ञानिक \'बी\' और सहायक विधि अधिकारी के पद पर भर्ती',
                'job_type' => 'regular',
                'direct_application' => 'online',
                'deputation_application' => 'online',
                'direct_application_url' => 'https://cpcb.nic.in/jobs/direct',
                'deputation_application_url' => 'https://cpcb.nic.in/jobs/deputation',
                'online_form_url' => 'https://cpcb.nic.in/jobs/apply',
                'posts' => [
                    ['title' => 'Scientist \'B\'', 'title_hi' => 'वैज्ञानिक \'बी\''],
                    ['title' => 'Assistant Law Officer', 'title_hi' => 'सहायक विधि अधिकारी']
                ]
            ],
            [
                'title' => 'Engagement of Consultants on Contractual Basis under NAMP Project',
                'title_hi' => 'नैम्प (NAMP) परियोजना के तहत अनुबंध के आधार पर सलाहकारों की नियुक्ति',
                'job_type' => 'contract',
                'direct_application' => 'offline',
                'deputation_application' => 'offline',
                'direct_application_form_name' => '5dgJV1Mz2zaMkMwA5Z8B.pdf',
                'direct_application_form_hi_name' => '5dgJV1Mz2zaMkMwA5Z8B.pdf',
                'deputation_application_form_name' => '5dgJV1Mz2zaMkMwA5Z8B.pdf',
                'deputation_application_form_hi_name' => '5dgJV1Mz2zaMkMwA5Z8B.pdf',
                'posts' => [
                    ['title' => 'Senior Consultant (Environment)', 'title_hi' => 'वरिष्ठ सलाहकार (पर्यावरण)'],
                    ['title' => 'Junior Consultant', 'title_hi' => 'कनिष्ठ सलाहकार']
                ]
            ],
            [
                'title' => 'Walk-in-interview for Junior Research Fellow (JRF) and Senior Research Fellow (SRF)',
                'title_hi' => 'जूनियर रिसर्च फेलो (जेआरएफ) और सीनियर रिसर्च फेलो (एसआरएफ) के लिए वॉक-इन-इंटरव्यू',
                'job_type' => 'contract',
                'direct_application' => 'offline',
                'deputation_application' => 'offline',
                'direct_application_form_name' => '5dgJV1Mz2zaMkMwA5Z8B.pdf',
                'direct_application_form_hi_name' => '5dgJV1Mz2zaMkMwA5Z8B.pdf',
                'deputation_application_form_name' => '5dgJV1Mz2zaMkMwA5Z8B.pdf',
                'deputation_application_form_hi_name' => '5dgJV1Mz2zaMkMwA5Z8B.pdf',
                'walk_in_interview_date' => Carbon::now()->addDays(15)->format('Y-m-d'),
                'posts' => [
                    ['title' => 'Junior Research Fellow (JRF)', 'title_hi' => 'जूनियर रिसर्च फेलो (जेआरएफ)'],
                    ['title' => 'Senior Research Fellow (SRF)', 'title_hi' => 'सीनियर रिसर्च फेलो (एसआरएफ)']
                ]
            ],
            [
                'title' => 'Recruitment for Administrative Posts (LDC, UDC, Assistant)',
                'title_hi' => 'प्रशासनिक पदों (एलडीसी, यूडीसी, सहायक) के लिए भर्ती',
                'job_type' => 'regular',
                'direct_application' => 'online',
                'deputation_application' => 'offline',
                'direct_application_url' => 'https://cpcb.nic.in/admin-jobs/apply',
                'online_form_url' => 'https://cpcb.nic.in/admin-jobs/apply',
                'deputation_application_form_name' => '5dgJV1Mz2zaMkMwA5Z8B.pdf',
                'deputation_application_form_hi_name' => '5dgJV1Mz2zaMkMwA5Z8B.pdf',
                'posts' => [
                    ['title' => 'Lower Division Clerk (LDC)', 'title_hi' => 'अवर श्रेणी लिपिक (एलडीसी)'],
                    ['title' => 'Upper Division Clerk (UDC)', 'title_hi' => 'प्रवर श्रेणी लिपिक (यूडीसी)'],
                    ['title' => 'Assistant', 'title_hi' => 'सहायक']
                ]
            ],
            [
                'title' => 'Engagement of IT Experts / Developers on Contractual Basis',
                'title_hi' => 'अनुबंध के आधार पर आईटी विशेषज्ञों / डेवलपर्स की नियुक्ति',
                'job_type' => 'contract',
                'direct_application' => 'online',
                'deputation_application' => 'online',
                'direct_application_url' => 'https://cpcb.nic.in/it-jobs',
                'deputation_application_url' => 'https://cpcb.nic.in/it-jobs/deputation',
                'online_form_url' => 'https://cpcb.nic.in/it-jobs/apply',
                'posts' => [
                    ['title' => 'Software Developer', 'title_hi' => 'सॉफ्टवेयर डेवलपर'],
                    ['title' => 'Database Administrator', 'title_hi' => 'डेटाबेस प्रशासक']
                ]
            ]
        ];



        $announcementTypes = [
            'notification',
            'Syllabus',
            'Corrigendum',
            'Shortlisted Candidates',
            'Admit Card',
            'Result',
            'Interview Schedule'
        ];

        $postCount = 0;
        $announcementCount = 0;

        foreach ($jobs as $jobData) {
            $jobId = DB::table('jobs')->insertGetId([
                'title' => $jobData['title'],
                'title_hi' => $jobData['title_hi'],
                'job_type' => $jobData['job_type'],
                'start_date' => Carbon::now()->subDays(rand(5, 30))->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(rand(10, 40))->format('Y-m-d'),
                'advertisement_file_name' => 'dummy_advertisement.pdf',
                'advertisement_file_hi_name' => 'dummy_advertisement_hi.pdf',
                'direct_application' => $jobData['direct_application'] ?? 'offline',
                'deputation_application' => $jobData['deputation_application'] ?? 'offline',
                'direct_application_form_name' => $jobData['direct_application_form_name'] ?? null,
                'direct_application_form_hi_name' => $jobData['direct_application_form_hi_name'] ?? null,
                'deputation_application_form_name' => $jobData['deputation_application_form_name'] ?? null,
                'deputation_application_form_hi_name' => $jobData['deputation_application_form_hi_name'] ?? null,
                'direct_application_url' => $jobData['direct_application_url'] ?? null,
                'deputation_application_url' => $jobData['deputation_application_url'] ?? null,
                'online_form_url' => $jobData['online_form_url'] ?? null,
                'walk_in_interview_date' => $jobData['walk_in_interview_date'] ?? null,
                'is_approved' => 1,
                'is_published' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($jobData['posts'] as $postData) {
                $postId = DB::table('job_posts')->insertGetId([
                    'job_id' => $jobId,
                    'title' => $postData['title'],
                    'title_hi' => $postData['title_hi'],
                    'is_approved' => 1,
                    'is_published' => 1,
                    'created_by' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $postCount++;

                // Add 2-3 random announcements for each post
                $numAnnouncements = rand(2, 3);
                $selectedTypes = (array) array_rand(array_flip($announcementTypes), $numAnnouncements);

                foreach ($selectedTypes as $type) {
                    DB::table('recruitment_announcements')->insert([
                        'job_post_id' => $postId,
                        'type' => 'notification', //strtolower(str_replace(' ', '_', $type)),
                        'title' => $type . ' for ' . $postData['title'],
                        'title_hi' => $postData['title_hi'] . ' के लिए ' . $type,
                        'start_date' => Carbon::now()->subDays(rand(1, 10))->format('Y-m-d'),
                        'end_date' => Carbon::now()->addDays(rand(10, 20))->format('Y-m-d'),
                        'file_name' => '5dgJV1Mz2zaMkMwA5Z8B.pdf',
                        'file_name_hi' => '5dgJV1Mz2zaMkMwA5Z8B.pdf',
                        'is_approved' => 1,
                        'is_published' => 1,
                        'created_by' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $announcementCount++;
                }
            }
        }

        $this->command->info("DummyJobSeeder completed: " . count($jobs) . " Jobs, {$postCount} Job Posts, and {$announcementCount} Recruitment Announcements created.");
    }
}
