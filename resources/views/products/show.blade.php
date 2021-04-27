@extends('layouts.app')

@section('content')
    <div class="container w-25">
        <div class="card">
            <div class="card-header text-center">
                <h5 class = "card-title">{{$product->name}}</h5>
            </div>
            <div class="card-body">
                <img class="img-thumbnail" src = "{{$signedUrl}}"/></p>

                <p class="card-text"> <strong> Description:</strong> {{$product->description}}</p>
                <p class="card-text"> <strong> Price:</strong> $ {{$product->price}}</p>
                <p class="card-text"> <strong> Amount in stock:</strong>

                    @if($product->amount == 0)
                    <span style = "color:red"> {{$product->amount}} </span>
                    @endif
                    @if($product->amount > 1000)
                    <span style = "color:green"> {{$product->amount}} </span>
                    @endif
                    @if($product->amount < 1000 && $product->amount > 0)
                    <span style = "color:rgb(253, 186, 0)"> {{$product->amount}} </span>
                    @endif
                </p>

            </div>
        </div>
        <br>

        @if ($product->pdf_generate == 0)
        <form action="{{ route('products.pdfGenerate', ['product'=>$product->id])}}" method = "POST">
            <input hidden value = "{{$signedUrl}}" name = "signedUrl" />
            <input hidden value = "{{$product->id}}" name = "id" />
            <input hidden value = "0" name = "view"/>
            <input hidden value = "0" name = "signed"/>
            <button type = "submit" class="btn btn-info float-right">
                @csrf

                <strong>Gerar pdf</strong>
            </button>
        </form>
        @else
        <form action="{{ route('products.pdfGenerate', ['product'=>$product->id])}}" method = "POST">
            <button type = "submit" class="btn btn-info float-right">
                @csrf
                <input hidden value = "{{$signedUrl}}" name = "signedUrl" />
                <input hidden value = "{{$product->id}}" name = "id" />
                <input hidden value = "1" name = "view"/>
                <input hidden value = "0" name = "signed"/>
                <strong>Ver pdf</strong>
            </button>
        </form>
        @endif
        @if($product->pdf_generate == 1 && $product->pdf_signed == 0)
            <br>
            <br>
            <button type = "submit" id = "signed_button" class="btn btn-success float-right">


                <strong>Assinar</strong>
            </button>
            <br>
            <br>

            <div style = "text-align:right; display:none;" id = "field_signed">
                <form action="{{ route('products.pdfGenerate', ['product'=>$product->id])}}" method = "POST">
                    @csrf
                    <input hidden value = "{{$product->id}}" name = "id" />
                    <input hidden value = "0" name = "view"/>
                    <input hidden value = "1" name = "signed"/>
                    <div class = "float-right">
                        <input type = "text" placeholder = "Sua assinatura" name = "signature" class = "form-control w-100" required />
                        <button type = "submit" id = "signed_button" class="btn btn-success float-right mt-1">Enviar</button>
                    </div>


                </form>
            </div>
        @endif


    </div>

<script>
    $(document).ready(function() {
        $('#signed_button').click(() => {
            $('#field_signed').slideToggle();
        })
    })
</script>
@endsection
