<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrand;
use Illuminate\Http\Request;
use App\Models\BinaryBrand;
use App\Services\UtilityService;
use App\Mail\SubMail;
use Session;

class BrandController extends Controller
{
    

    public function __construct(UtilityService $utility)
    {
        $this->utility = $utility;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = auth()->user()->binaryBrand()->get();
        return view('pages.brand.index', ['brands' => $brands]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBrand $request)
    {
        // Collection  of Request data
        $data = [
            'brand_name'    => $request->brand_name,
            'slug'          => str_slug($request->brand_name, '-'),
            'from_name'     => $request->from_name,
            'from_email'    => $request->from_email,
            'reply_to'      => $request->reply_to,
            'allowed_files' => $request->allowed_files,
            'query_string'  => null,
            'brand_logo'    => $request->brand_logo,
            'setting'      => $request->settings,
        ];

        // Creating new Brand
        $new = auth()->user()->binaryBrand()->create($data);

        $new->defaultList()->create([
            'user_id' => auth()->user()->id
        ]);

        // Session Message
        Toastr()->success('Created Succesfully', $data['brand_name'], ["positionClass" => "toast-bottom-right"]);        

        // redirecting to show page
        return redirect()->route('brand.show', $new->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        // find the brand
        $brand = $this->utility->getBrand($slug);

        // All Campaigns
        $campaigns = $this->utility->getLatestCampaigns($slug);

        return view('pages.brand.show', [
            'brand' => $brand,
            'campaigns' => $campaigns
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $brand = $this->utility->getBrand($slug);
        
        return view('pages.brand.edit', [
            'brand' => $brand
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreBrand $request, $slug)
    {
        // Collection  of Request data
        $data = [
            'brand_name'    => $request->brand_name,
            'slug'          => str_slug($request->brand_name, '-'),
            'from_name'     => $request->from_name,
            'from_email'    => $request->from_email,
            'reply_to'      => $request->reply_to,
            'allowed_files' => $request->allowed_files,
            'query_string'  => null,
            'brand_logo'    => $request->brand_logo,
            'setting'      => $request->settings,
        ];

        // updating
        $this->utility->getBrand($slug)->update($data);

        // Session Message
        Toastr()->info('Updated Succesfully', $data['brand_name'], ["positionClass" => "toast-bottom-right"]);    

        // redirecting
        return redirect()->route('brand.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $this->utility->getBrand($slug)->delete();

        Toastr()->error('Deleted Successfully', 'Brand', ["positionClass" => "toast-bottom-right"]);        

        return redirect()->route('brand.index');
    }


    public function join($slug, $query)
    {
        $brand = $this->utility->getBrandPublic($slug);
        if ($brand->defaultList && ( $_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['email']) )) {
            
            $email = $_GET['email'];

            $sub = $brand->defaultList->binarySubs()->firstOrcreate([
                'email' => $email
            ]);

            \Mail::to($email)
                ->send(new SubMail($email));

            return response()->json('Subscriber!');
        }
    }
}
