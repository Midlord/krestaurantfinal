<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RestaurantRequest;
use App\Models\Restaurant;
use App\User;
use App\Models\Photo;
use Carbon\Carbon;
use DB;
use App\Models\Log;

class RestaurantController extends Controller
{
    private $restaurant;

    public function __construct(Restaurant $restaurant)
    {
        $this->restaurant = $restaurant;
    }
    public function index()
    {
        $restaurants = Restaurant::with('user')->orderby('id', 'desc')->get();
        return view ('admin.restaurants.index',compact('restaurants'));
    }

    public function create()
    {
        $users = User::where('type','owner')->get();
        
        return view ('admin.restaurants.create',compact('users'));
    }


    public function store(RestaurantRequest $request)
    {

        DB::table('logs')->insert([
            ['name' => 'Added '.$request->get('name').' in Restaurants at '.\Carbon\Carbon::now()->format('M d, Y h:i a').'',
             'created_at' =>\Carbon\Carbon::now()->format('Y-m-d'), 
             'updated_at' =>\Carbon\Carbon::now()->format('Y-m-d')
         ]
        ]);
    // create restaurant with featured image
        $restaurant= $this->restaurant->create($request->all());
        if ($request->hasFile('image'))
            {
                $file = $request->file('image');
                $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString()); 
                $name = $timestamp. '-' .$file->getClientOriginalName();
                $restaurant->image = $name;
                $file->storeAs('public/uploads/images', $name);                 
            }  

    // create restaurant with multiple images
        if ($request->hasFile('images'))
        {
            foreach($request->file('images') as $photo)
            { 
                $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString()); 
                $name = $timestamp. '-' .$photo->getClientOriginalName();
                $photo->image = $name;
                $photo->storeAs('public/uploads/images', $name);

                $photo = Photo::create([
                    'restaurant_id' => $restaurant->id,
                    'image' => $name,
                ]);
                
            }             
        }  
        // create restaurant menus
        if ($request->hasFile('images_menu'))
        {
            foreach($request->file('images_menu') as $menu)
            { 
                $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString()); 
                $name = $timestamp. '-' .$menu->getClientOriginalName();
                $menu->image = $name;
                $menu->storeAs('public/uploads/images', $name);

                $menu = Menu::create([
                    'restaurant_id' => $restaurant->id,
                    'menuName' => $request->menu,
                    'price' => $request->price,
                    'image' => $name,
                    ]);
                
            }             
        }






    //Display a successful message upon save
        $request->session()->flash('message', $restaurant->name. ' Created successful');
        return redirect()->back();
    }

    public function show($id)
    {
       $restaurant = $this->restaurant->with(['photos'])->findOrfail($id);
       return view('admin.restaurants.show',compact('restaurant'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $restaurant = Restaurant::findOrfail($id);

        return view('admin.restaurants.edit', compact('restaurant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Validating title and body field
        $this->validate($request, [
            'name'=>'required|max:100',
            'description' =>'required',
            'address'=>'required|max:100',
            'longitude' =>'required',
            'latitude' =>'required',
            'about' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

         DB::table('logs')->insert([
            ['name' => 'Updated '.$request->get('name').' in Restaurants at '.\Carbon\Carbon::now()
            ->format('M d, Y h:i a').'',
             'created_at' =>\Carbon\Carbon::now()->format('Y-m-d'), 
             'updated_at' =>\Carbon\Carbon::now()->format('Y-m-d')
         ]
        ]);



        $restaurant = Restaurant::findOrFail($id)->update($request->all());

       
        $request->session()->flash('message','Updated successful');
        return redirect()->route('admin.restaurants.edit', $id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $restaurants = Restaurant::findOrFail($id);
        $restaurants->delete();


        DB::table('logs')->insert([
            ['name' => 'Remove '.$restaurants->name.' in Restaurants at '.\Carbon\Carbon::now()->format('M d, Y h:i a').'',
             'created_at' =>\Carbon\Carbon::now()->format('Y-m-d'), 
             'updated_at' =>\Carbon\Carbon::now()->format('Y-m-d')
         ]
        ]);



        request()->session()->flash('message', 'Delete successful');
        return redirect()->route('admin.restaurants.index');
    }

    public function archive()
    {
        $restaurants = Restaurant::onlyTrashed()
                    ->get();

        return view ('admin.restaurants.archive',[
            'restaurants' => $restaurants
        ]);
            
    }

    public function rest($id)
    {
        $restaurant = Restaurant::onlyTrashed()
                ->where('id', $id)
                ->restore();
        $restaurant1 = Restaurant::findOrfail($id);

        DB::table('logs')->insert([
        ['name' => 'Restore '.$restaurant1->name.' in Restaurants at '.\Carbon\Carbon::now()->format('M d, Y h:i a').'',
        'created_at' =>\Carbon\Carbon::now()->format('Y-m-d'), 
        'updated_at' =>\Carbon\Carbon::now()->format('Y-m-d')
        ]
        ]);

        request()->session()->flash('message', 'Restore successful');
        return redirect()->route('admin.restaurants.archive');
    }
}
