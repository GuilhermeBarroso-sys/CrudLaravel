@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('products.store')}}" class = "form-horizontal" method = "POST">
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
                <input type="number" placeholder = "Product Price" class="form-control" name = "price" required>
            </div>
            <div class="row">
                <label for="Amount">Amount</label>
                <input type="number" placeholder = "Product Amount"class="form-control" name = "amount" required>
            </div>
            <br>
            <div class="row">

                <button type="submit" class = "btn btn-primary">Create</button>
            </div>
        </div>
    </form>
</div>

@endsection
