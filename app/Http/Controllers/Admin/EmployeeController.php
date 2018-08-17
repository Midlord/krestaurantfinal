<?php

namespace App\Http\Controllers\Admin;

use Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Employees\Employee;
class EmployeeController extends Controller
{

    public function index()
    {   
        $users = User::get();
        $employees = Employee::get();
        return view('admin.employees.index',[
            'employees' => $employees
        ]);
    }


    public function create()
    {
        return view('admin.employees.create');
    }


    public function store(Request $request)
    {
            $employee = Employee::create([
                'firstName' => $request->firstName,
                'middleName' => $request->middleName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'username' => $request->username,
                'role' => 'admin',
                'phone' => $request->phone,
                'password' => Hash::make($request->password)
            ]);
      

        session()->flash('status', 'You have successfully added new Admin record.');
        session()->flash('type', 'success');
        return redirect()->route('admin.employees.index');

    }


    public function show($id)
    {
        $employee = Employee::findOrfail($id);
        return view('admin.employees.show',compact('employee'));
    }


    public function edit($id)
    {
        $employee = Employee::findOrfail($id);

        return view('admin.employees.edit',compact('employee'));
    }


    public function update(Request $request, $id)
    {
        $employee = Employee::findOrfail($id);
        $employee = Employee::update([
            'firstName' => $request->firstName,
            'middleName' => $request->middleName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'username' => $request->username,
            'role' => 'admin',
            'phone' => $request->phone
        ]);

        session()->flash('status', 'You have successfully update new Admin record.');
        session()->flash('type', 'success');
        return redirect()->route('admin.employees.index');
    }


    public function destroy(Employee $employee)
    {
        $employee->delete();
        session()->flash('status', 'Successfully deleted!');
        session()->flash('type', 'success');
        return response('success', 200);
    }
}
