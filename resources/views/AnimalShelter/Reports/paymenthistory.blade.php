@extends("main")
@section("header")
Subscription History
@endsection
@push("css")
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css">   
@endpush
@section("content")
<div class="row">
    <div class="col-sm">
        <div class="card">
            <div class="card-header">
            <form action="{{route('searchpaymenthistory.shelter')}}" method="POST">
                <div style="display:flex">
                        @csrf
                        <div class="col-sm">
                            <label for="">From Date</label><span><input type="date" name="fromdate" required class="form-control" id=""></span>&nbsp&nbsp
                        </div>
                        <div class="col-sm">
                            <label for="">To Date</label><span><input type="date" name="todate" required class="form-control" id=""></span>
                        </div>
                        <div class="col-sm">
                            <span><button type="submit" style="margin-top:30px" class="btn btn-danger">Generate Report</button></span>
                        </div>
                    <div style="float:right">
                        <span><button id="print"type="button"><i class="fa fa-print"></i> Print Report</button></span>
                    </div>
                </div>
             </form>
            </div>
            <div class="card-body" id="printreport">
                <table id="datatable" class="table table-light table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th style="text-align:center">Subscription Name</th>
                            <th style="text-align:center">Post Credits</th>
                            <th style="text-align:center">Promo Price</th>
                            <th style="text-align:center">Date Subscribed</th>
                            <th style="text-align:center">Subscription Expiry Date</th>
                            <th style="text-align:center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($subscription as $sub)
                        <tr>
                            <td>{{$sub->id}}</td>
                            <td  style="text-align:center">
                                {{$sub->subscription->sub_name}}
                            </td> 
                            <td  style="text-align:center">
                                {{$sub->subscription->sub_credit}}
                            </td> 
                            <td  style="text-align:center">PHP {{$sub->subscription->sub_price}}</td>
                            <td style="text-align:center">
                                {{ \Carbon\Carbon::parse($sub->subscription->updated_at)->format('F d, Y h:i A')}}
                            </td>
                            <td  style="text-align:center">
                                {{$sub->expiry_date}}
                            </td>
                            <td  style="text-align:center">
                                <button disabled class="btn btn-success">Completed</button>
                            </td>
                        </tr> 
                    @endforeach
                    @if(empty($sub))   
                        <h6 class="alert alert-danger">No Subscription History</h6>
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{route('reports.shelter')}}"><button class="btn btn-danger">Back</button></a>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.js" integrity="sha512-BaXrDZSVGt+DvByw0xuYdsGJgzhIXNgES0E9B+Pgfe13XlZQvmiCkQ9GXpjVeLWEGLxqHzhPjNSBs4osiuNZyg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js" integrity="sha512-t3XNbzH2GEXeT9juLjifw/5ejswnjWWMMDxsdCg4+MmvrM+MwqGhxlWeFJ53xN/SBHPDnW0gXYvBx/afZZfGMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    var table = $('#datatable').DataTable();
});
$('#print').click(function(){
    $("#printreport").print();
});
</script>
@endpush