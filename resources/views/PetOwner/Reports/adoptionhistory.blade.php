@extends("mainpetowner")
@section("header")
Adoption History
@endsection
@push("css")
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css">   
@endpush
@section("content")
<div class="row">
    <div class="col-sm">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                <table id="datatable" class="table table-light table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Adopted Pet Photo</th>
                            <th>Adopted Pet Name</th>
                            <th>Adopter Photo</th>
                            <th>Adopter Name</th>
                            <th>View Application</th>
                            <th>Date Pet Adopted</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($adoption as $adopt)
                        <tr>
                            <td>{{$adopt->id}}</td>
                            <td>
                            <img src="{{asset('uploads/adopts/'.$adopt->animal->animal_image)}}" width="70px" height="70px" alt="photo">
                            </td>
                            <td>{{$adopt->animal->name}}</td>
                            <td>
                            <img src="{{asset('/phpcode/adopter/'.$adopt->adopter->photo)}}" width="70px" height="70px" alt="photo">
                            </td>
                            <td>{{$adopt->adopter->fname}} {{$adopt->adopter->lname}}</td>
                            <td>
                            <a href=""><i class="far fa-eye"></i></a>
                            </td>
                            <td>{{$adopt->animal->updated_at}}</td>
                            <td>
                                <button disabled class="btn btn-success">Adopted</button>
                            </td>
                        </tr> 
                    @endforeach
                    @if(empty($adopt))   
                        <h6 class="alert alert-danger">No Adoption History</h6>
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{route('reports')}}"><button class="btn btn-danger">Back</button></a>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
          var table = $('#datatable').DataTable();
        });
    </script>
@endpush