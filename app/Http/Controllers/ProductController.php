<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\User;

use Config;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config as FacadesConfig;
use Illuminate\Support\Facades\Storage;
use services\Aws;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $products = DB::select('select * from products where user_id = '. Auth::user()->id );
        return view('products/index')->with(['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('products/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $path = $request->file('image')->store('id/'.$request->input('user_id').'/products', 's3');
        Storage::disk('s3')->setVisibility($path,'public');
        Product::create([
            'name' => $request->input('name'),
            'user_id' => $request->input('user_id'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'amount' => $request->input('amount'),
            'filename' => basename($path),
            'url' => Storage::disk('s3')->url($path),
        ]);
        return redirect()->route('products.index');


    }
    // Laravel: myFunction->all() ===  Node.js: myFunction.all()

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        $prod = json_decode($product);
        $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
        $bucket = Config::get('filesystems.disks.s3.bucket');

        $command = $client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => 'id/'.$prod->user_id.'/'.'products/'.$prod->filename  // file name in s3 bucket which you want to access
        ]);

        $request = $client->createPresignedRequest($command, '+1 minutes');

        // Get the actual presigned-url
        $presignedUrl = (string)$request->getUri();

        return view('products/show')->with(['product'=>$prod, 'signedUrl'=> $presignedUrl]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);

        $prod = json_decode($product);
        $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
        $bucket = Config::get('filesystems.disks.s3.bucket');

        $command = $client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => 'id/'.$prod->user_id.'/'.'products/'.$prod->filename  // file name in s3 bucket which you want to access
        ]);

        $request = $client->createPresignedRequest($command, '+1 minutes');

        // Get the actual presigned-url
        $presignedUrl = (string)$request->getUri();
        return view('products/edit')->with(['product'=>$product, 'signedUrl'=> $presignedUrl]);
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

            Product::find($id)->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'amount' => $request->input('amount'),
            ]);
            return redirect()->route('products.index');









    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::destroy($id);
        return redirect()->route('products.index');

    }
}
