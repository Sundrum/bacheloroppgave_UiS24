@extends(layouts.app)
@section('content')
<section class="bg-white card-rounded">
    <div class="row mt-3 text-center">
    </div>
    <div class="row text-center mt-5">
        <div class="col-12">
            <h4>Details for {{$subscription->name}}</h4>
        </div>
    </div>
</section>
@endsection