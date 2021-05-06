<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\PDF;
use Dompdf\Autoloader;

use setasign\Fpdi\PdfParser;
use Config;
use FPDF;
use Illuminate\Http\File;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\FpdfTpl;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\Tcpdf\Fpdi;
use setasign\Fpdi\Tfpdf\Fpdi as TfpdfFpdi;

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
        $path = $request->file('image')->store('id/'.$request->input('user_id').'/products/images', 's3');

        Product::create([
            'name' => $request->input('name'),
            'user_id' => $request->input('user_id'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'amount' => $request->input('amount'),
            'filename' => basename($path),
            'url' => Storage::disk('s3')->url($path),
            'services_terms' => 0,
            'pdf_generate' => 0,
            'pdf_signed' => 0,

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
            'Key' => 'id/'.$prod->user_id.'/products/images/'.$prod->filename  // file name in s3 bucket which you want to access
        ]);

        $request = $client->createPresignedRequest($command, '+15 seconds');

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
            'Key' => 'id/'.$prod->user_id.'/'.'products/images/'.$prod->filename  // file name in s3 bucket which you want to access
        ]);

        $request = $client->createPresignedRequest($command, '+15 seconds');

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
            $path = Product::find($id)->filename;
            $filename = $request->file('image');

            if(!($filename === null)) {
                $path = $request->file('image')->store('id/'.$request->input('user_id').'/products/images', 's3');
            }

            Product::find($id)->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'amount' => $request->input('amount'),
                'filename' => basename($path),
                'url' => Storage::disk('s3')->url($path),
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

   /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request

     */
    public function pdfGenerate(Request $request) {
        $id = $request->input("id");
        $servicesTerms = $request->input("servicesTerms");
        $signed = $request->input("signed");
        $signed == "1" ? $client_signature = $request->input("signature") : $client_signature = "";

        $view = $request->input("view");
        $product = Product::find($id);
        $prod = json_decode($product);
        $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
        $bucket = Config::get('filesystems.disks.s3.bucket');
        $command = $client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => 'id/'.$prod->user_id.'/'.'products/images/'.$prod->filename  // file name in s3 bucket which you want to access
        ]);
        $request = $client->createPresignedRequest($command, '+15 seconds');
        // Get the actual presigned-url
        $presignedUrl = (string)$request->getUri();
        $pdf = \PDF::loadView('products.pdfGenerateShow', compact('product', 'presignedUrl','client_signature'));
        if($prod->pdf_generate == 0) {
        $content = $pdf->download()->getOriginalContent();

        Storage::disk('s3')->put('id/'.$prod->user_id.'/products/product_pdf/'.$prod->name.'.pdf', $content);
        $product->update(
            [
                'pdf_generate' => 1
            ]
        );
        return redirect("/products/".$id);
        }
        else if($view == "1") {
            $tag_Y = 218; // Simulação da Tag Y
            /*$parser = new \Smalot\PdfParser\Parser();
            $pdfget = $parser->parseFile(public_path('cartaoDePonto.pdf'));
            $text = $pdfget->getDetails();
            Pegar as Tags
            */
            $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
            $bucket = Config::get('filesystems.disks.s3.bucket');
            $command = $client->getCommand('GetObject', [
                'Bucket' => $bucket,
                'Key' => 'id/'.$prod->user_id.'/'.'products/product_pdf/'.$prod->name.'.pdf' // file name in s3 bucket which you want to access
            ]);
            $request = $client->createPresignedRequest($command, '+15 minutes');
            // Get the actual presigned-url
            $presignedUrl = (string)$request->getUri();


            return redirect($presignedUrl);
            /***Codigo original !! */
            /*$client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
            $bucket = Config::get('filesystems.disks.s3.bucket');

            $command = $client->getCommand('GetObject', [
                'Bucket' => $bucket,
                'Key' => 'id/'.$prod->user_id.'/'.'products/product_pdf/'.$prod->name.'.pdf' // file name in s3 bucket which you want to access
            ]);
            $request = $client->createPresignedRequest($command, '+15 minutes');
            // Get the actual presigned-url
            $presignedUrl = (string)$request->getUri();
            dd($presignedUrl);
            return redirect($presignedUrl);
            */

        }
        else if($servicesTerms == "on") {

            if($signed == "1"){


                $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
                $bucket = Config::get('filesystems.disks.s3.bucket');

                $command = $client->getCommand('GetObject', [
                    'Bucket' => $bucket,
                    'Key' => 'id/'.$prod->user_id.'/'.'products/product_pdf/'.$prod->name.'.pdf' // file name in s3 bucket which you want to access
                ]);
                $request = $client->createPresignedRequest($command, '+15 minutes');
                // Get the actual presigned-url
                $presignedUrl = (string)$request->getUri();
                $fileContent = file_get_contents($presignedUrl, 'rb');
                $pdfedit = new \setasign\Fpdi\Fpdi();
                $pdfedit->setSourceFile(StreamReader::createByString($fileContent));
                $tpl = $pdfedit->importPage(1);
                $pdfedit->AddPage();
                $pdfedit->useTemplate($tpl);
                $pdfedit->SetFont('Helvetica');
                $pdfedit->SetFontSize('15'); // set font size
                $pdfedit->SetXY(50, 275.5); // set the position of the box
                $pdfedit->Cell(0, 0, $client_signature, 0, 0, 'L'); // add the text, align to Center of cell
                //$fileDownloadPath = public_path($prod->name.'.pdf');

                Storage::disk('s3')->put('id/'.$prod->user_id.'/'.'products/product_pdf/'.$prod->name.'.pdf',$pdfedit->Output("S"));


                /*Storage::disk('s3')->put('id/'.$prod->user_id.'/products/product_pdf/'.$prod->name.'.pdf', $pdfedit->setSourceFile(StreamReader::createByString($fileContent)));*/
                $product->update(
                    [
                        'pdf_signed' => 1
                    ]
                );

                return redirect("/products/".$id);
                }
        }
        else {
            return redirect("/products/".$id);
        }





     }
     /*public function pdfGenerateShow(Request $request) {
        $id = $request->input("id");
        $product = Product::find($id);
        $prod = json_decode($product);
        $client = Storage::disk('s3')->getDriver()->getAdapter()->getClient();
        $bucket = Config::get('filesystems.disks.s3.bucket');

        $command = $client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => 'id/'.$prod->user_id.'/'.'products/images/'.$prod->filename  // file name in s3 bucket which you want to access
        ]);
        $request = $client->createPresignedRequest($command, '+15 seconds');
        // Get the actual presigned-url
        $presignedUrl = (string)$request->getUri();
        $client_signature = "Guilherme Barroso";
        $pdf = \PDF::loadView('products.pdfGenerateShow', compact('product', 'presignedUrl','client_signature'));
        $content = $pdf->download()->getOriginalContent();
        //Storage::disk('s3')->put('id/'.$prod->user_id.'/products/product_pdf/'.$prod->name.'.pdf', $content);

        return $pdf->stream();
    //Storage::put('products/pdf/nameeeeeee.pdf', $content);

}
*/
}
