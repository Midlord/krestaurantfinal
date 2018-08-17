<?php

namespace App\Http\Controllers\Admin;

use Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Employees\Employee;
class UserController extends Controller
{
    private $user;


    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {   
        $users = User::get();
        $employees = Employee::get();
        return view('admin.users.index',[
            'users' => $users,
            'employees' => $employees
        ]);
    }


    public function create()
    {
        return view('admin.users.create');
    }


    public function store(Request $request)
    {

        $user = $this->user->create([
            'firstName' => $request->firstName,
            'middleName' => $request->middleName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'username' => $request->username,
            'role' => $request->role,
            'phone' => $request->phone,
            'password' => bcrypt($request->password)
        ]);
        session()->flash('status', 'You have successfully added new User record.');
        session()->flash('type', 'success');
        return redirect()->route('admin.users.index');

    }


    public function show($id)
    {
        $user = $this->user->findOrfail($id);
        return view('admin.users.show',compact('user'));
    }


    public function edit($id)
    {
        $user = $this->user->findOrfail($id);
        $employee = Employee::findOrfail($id);

        return view('admin.users.edit',compact('user','employee'));
    }


    public function update(Request $request, $id)
    {
        $user = $this->user->findOrfail($id);

        $user = $this->user->create([
            'firstName' => $request->firstName,
            'middleName' => $request->middleName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'username' => $request->username,
            'role' => $request->role,
            'phone' => $request->phone,
            'password' => bcrypt($request->password)
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        session()->flash('status', 'Successfully deleted!');
        session()->flash('type', 'success');
        return response('success', 200);
    }
}
