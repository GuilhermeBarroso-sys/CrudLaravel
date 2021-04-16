@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class = "text-center">Create a Product</h1>
    <hr>
    <form action="{{route('products.store')}}" class = "form-horizontal" method = "POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">

            <div class="row">
                <label for="Name">Name</label>
                <input type="text" placeholder = "Product Name" class="form-control" name = "name" required>
            </div>

            <div class="row">
                <label for="Description">Description</label>
                <input type="text" placeholder = "Product Description" class="form-control" name = "description">
            </div>
            <div class="row">
                <label for="Price">Price</label>
                <input type="number" onchange = "setTwoNumberDecimal"  min="1" max="99999999" step="0.25" placeholder = "Product Price" class="form-control" name = "price" required>
            </div>
            <div class="row">
                <label for="Amount">Amount</label>
                <input type="number" placeholder = "Product Amount"class="form-control" name = "amount" required>
            </div>
            <br>
            <div class="row">
                <label for="Product Image">Product Image</label>
                <input type="file" class="form-control-file" name = "image" id = "image">
            </div><br>
            <div class="row">

                <button type="submit" class = "btn btn-primary">Create</button>
            </div>
        </div>

    </form>
</div>
<script>
    function setTwoNumberDecimal(event) {
        this.value = parseFloat(this.value).toFixed(2);
    }
</script>
@endsection
