<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CugAdmissionsController extends Controller
{
    /**
     * Look up student information from CUG Admissions system by phone number.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lookup(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^0[235][0-9]{8}$/'],
        ], [
            'phone.regex' => 'Please enter a valid Ghana phone number (e.g., 0241234567)',
        ]);

        $phone = $request->input('phone');

        try {
            // TODO: Replace this with actual CUG Admissions API endpoint
            // For now, this is a placeholder that simulates the API call
            
            $apiUrl = config('services.cug_admissions.url', 'https://admissions.cug.edu.gh/api');
            $apiKey = config('services.cug_admissions.key', '');

            // Uncomment and configure when you have the actual API details:
            /*
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
            ])->timeout(10)->get($apiUrl . '/student/lookup', [
                'phone' => $phone,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (!empty($data['found']) && $data['found'] === true) {
                    return response()->json([
                        'found' => true,
                        'data' => [
                            'first_name' => $data['first_name'] ?? '',
                            'last_name' => $data['last_name'] ?? '',
                            'email' => $data['email'] ?? '',
                            'student_id' => $data['student_id'] ?? '',
                            'programme' => $data['programme'] ?? '',
                            'level' => $data['level'] ?? '',
                        ],
                    ]);
                }
            }
            */

            // DEMO MODE: Simulate API response for testing
            // Remove this block when connecting to actual API
            $demoStudents = $this->getDemoStudents();
            
            if (isset($demoStudents[$phone])) {
                return response()->json([
                    'found' => true,
                    'data' => $demoStudents[$phone],
                ]);
            }

            return response()->json([
                'found' => false,
                'message' => 'Phone number not found in the CUG admissions system. Please fill in your details manually.',
            ]);

        } catch (\Exception $e) {
            Log::error('CUG Admissions API Error: ' . $e->getMessage());

            return response()->json([
                'found' => false,
                'error' => true,
                'message' => 'Unable to connect to the admissions system. Please fill in your details manually.',
            ], 503);
        }
    }

    /**
     * Demo students for testing purposes.
     * Remove this method when connecting to actual API.
     */
    private function getDemoStudents(): array
    {
        return [
            '0241234567' => [
                'first_name' => 'Kofi',
                'last_name' => 'Mensah',
                'email' => 'kofi.mensah@student.cug.edu.gh',
                'student_id' => 'CUG/2024/0001',
                'programme' => 'BSc Computer Science',
                'level' => '200',
            ],
            '0551234567' => [
                'first_name' => 'Ama',
                'last_name' => 'Asante',
                'email' => 'ama.asante@student.cug.edu.gh',
                'student_id' => 'CUG/2024/0002',
                'programme' => 'BSc Nursing',
                'level' => '100',
            ],
            '0201234567' => [
                'first_name' => 'Kwame',
                'last_name' => 'Owusu',
                'email' => 'kwame.owusu@student.cug.edu.gh',
                'student_id' => 'CUG/2024/0003',
                'programme' => 'BA Business Administration',
                'level' => '300',
            ],
        ];
    }
}

