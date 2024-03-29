@extends("petownertempmain")
@section("header")
Welcome Pet Owner!
@endsection
@push('css')
<link href="{{url('css/secondintro.css')}}" rel="stylesheet">
@endpush
@section("content")
 <div class="row">
        <!-- Dashboard Content -->
        <div class="col-xl col-lg-3">
            <div class="card shadow mb-4">
                <!-- Dashboard header -->
                <div
                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h5>Adoption Fee categories:</h5> 
                </div>
            </div>  
        </div>
    </div>
@if($dog)
@include('PetOwner.SettingUp.DogFee')
@elseif($cat)
@include('PetOwner.SettingUp.CatFee')
@elseif($both)
@include('PetOwner.SettingUp.BothFee')
@endif

@endsection
@push('js')
<script type="text/javascript">
$(document).ready(function(){
    $('input[type="checkbox"]').attr('checked', status);
});
</script>
@endpush