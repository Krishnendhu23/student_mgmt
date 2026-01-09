<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentCreateRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the students with sortable columns.
     */
    public function index(Request $request)
    {
        //Listing Columns 
        $columns = [
                    'admission_no' => 'Admission No',
                    'name' => 'Name',
                    'age' => 'Age',
                    'gender' => 'Gender',       // non-sortable
                    'mark' => 'Mark',
                    'result' => 'Result',
                    'actions' => 'Actions'      // non-sortable
                ];

        //Allowed sortable columns
        $allowedSorts = ['admission_no', 'name', 'age', 'mark', 'result'];

        // Query parameters for sort and orer; by default admission no descending
        $sortBy  = in_array($request->get('sort'), $allowedSorts)? $request->get('sort'): 'admission_no';
        $order   = $request->get('order') === 'asc' ? 'asc' : 'desc';

        $students = Student::orderBy($sortBy, $order)
                            ->paginate(10)
                            ->appends(['sort' => $sortBy, 'order' => $order]);

        return view('students.index', compact('students','columns', 'allowedSorts', 'sortBy', 'order'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(StudentCreateRequest $request)
    {
        try {

            $data = $request->only(['name', 'gender', 'age', 'mark']);
            $data['result'] = Student::calculateResult($data['mark']); //result calculation

            // Create student
            $student = Student::create($data);


            return response()->json([
                'status'  => true,
                'message' => 'Student data saved successfully',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to create student',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display student edit form.
     */
    public function show(string $id)
    {
        $student = Student::find($id);
        return view('students.create', compact('student'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(StudentCreateRequest $request, string $id)
    {
        try {
            $student = Student::find($id);
            if (!$student) {
                return response()->json(['message' => 'Student not found'], 404);
            }
             
            $data = $request->only(['name', 'gender', 'age', 'mark']);
            $data['result'] = Student::calculateResult($data['mark']); //result calculation

            $student->update($data);
 
            return response()->json([
                'status'  => true,
                'message' => 'Student updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update student info. Please try again.'], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }
        $student->delete();
        return response()->json(['message' => 'Student data deleted successfully']);
    }
}
