<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentStoreRequest;
use App\Models\Student;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function store(StudentStoreRequest $request)
    {
        $data               = $request->validated();
        $data['api_token']  = Str::random(60);
        $data['merchantId'] = auth()->id();

        $student = Student::firstOrCreate(
            ['id' => $data['id']],
            $data
        );

        $student->makeVisible('api_token');

        return $student;
    }
}
