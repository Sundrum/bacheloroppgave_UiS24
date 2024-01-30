<div class="card card-rounded">
    @if(isset($product) && $product->product_image_url)
        <div class="row mt-3 mb-3 justify-content-center">
                <div class="col text-center">
                        <img src="{{$product->product_image_url ?? ''}}" width="120">          
                </div>
        </div>
    @endif         

    <div class="row justify-content-center mt-3 mb-3">
        <div class="col-12">
            <div class="col-md-12">
                <form id="edit" action="/admin/update/product" method="POST">
                    @csrf
                    <input type="hidden" id="product_id" name="product_id" value="{{$product->product_id ?? ''}}">
                    <div class="form-group">
                        <label class="form-label" for="name">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Product Name" value="{{$product->product_name ?? ''}}" tabindex="1" required>
                    </div>                            
                    <div class="form-group">
                        <label class="form-label" for="productnumber">Product Number</label>
                        <input type="text" class="form-control" id="productnumber" name="productnumber" placeholder="Product Number" value="{{$product->productnumber ?? '' }}" required @if(isset($product))disabled @endif>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="name">Image</label>
                        <input type="text" class="form-control" id="product_image_url" name="product_image_url" placeholder="Image URL" value="{{$product->product_image_url ?? ''}}" tabindex="2">
                    </div>                            
                    <div class="form-group">
                        <label class="form-label" for="productnumber">Description</label>
                        <input type="text" class="form-control" id="product_description" name="product_description" placeholder="Description" value="{{$product->product_description ?? ''}}" tabindex="3">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="name">Product Type</label>
                        
                        <select class="custom-select form-control" id="product_type" name="product_type" required>
                            @foreach($product_type as $row)
                                <option value="{{$row->product_type_id}}" @if(isset($product->product_type) && $row->product_type_id==$product->product_type) selected="selected" @endif> {{$row->product_type_name}} </option>
                            @endforeach
                        </select>
                    </div>                            
                    <div class="form-group">
                        <label class="form-label" for="productnumber">Documentation</label>
                        <input type="number" class="form-control" id="document_id_ref" name="document_id_ref" placeholder="Documentation" value="{{$product->document_id_ref ?? '1'}}" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn-7g">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
