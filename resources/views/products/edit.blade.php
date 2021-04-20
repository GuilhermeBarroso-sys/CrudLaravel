@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class = "text-center">Edit a Product</h1>
    <hr>
    <form action="{{route('products.update', ['product '/*<- Nome do parametro | valor do parametro ->*/ => $product->id])}}" class = "form-horizontal" method = "POST" enctype="multipart/form-data">
        @csrf <!--token-->
        @method('PUT') <!--Informar que o metodo  de edição. -->
        <div class="form-group">
                <input type = "hidden" name = "user_id" value = "{{$product->user_id}}"/>
            <div class="row">
                <label for="Name">Name</label>
                <input type="text" value = "{{$product->name}}" placeholder = "Product Name" class="form-control" name = "name" required>
            </div>
            <div class="row">
                <label for="Description">Description</label>
                <input type="text" value = "{{$product->description}}" placeholder = "Product Description" class="form-control" name = "description">
            </div>
            <div class="row">
                <label for="Price">Price</label>
                <input type="number" value = "{{$product->price}}" onchange = "setTwoNumberDecimal"  min="1" max="99999999" step="0.25" placeholder = "Product Price" class="form-control" name = "price" required>
            </div>
            <div class="row">
                <label for="Amount">Amount in stock</label>
                <input type="number" value = "{{$product->amount}}" placeholder = "Product Amount"class="form-control" name = "amount" required>
            </div>
            <br>
            <div class="row">
                <p><strong>Product Current Image:</strong></p>
            </div>
            <div class="row">

                <img src = "{{$signedUrl}}" width = "300" height = "300" class="img-thumbnail">
            </div>
            <br>
            <div class = "row">
                <input type="file" name = "image"  id="customFile" />

            </div>
            <br>

            <div class="row">

                <button type="submit" class = "btn btn-primary">Update</button>
            </div>
        </div>
    </form>
</div>
<script src = "https://code.jquery.com/jquery-3.6.0.min.js"></script>


@endsection
