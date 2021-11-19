<form action="{{route('dog.fee.petowner')}}" method="POST">
@csrf
<main style="margin-top:30px" class="grid">
    <label class="option_item">
    <input type="checkbox" name="fee[]" value="Free" class="checkbox">
    <div class="option_inner kitten">
        <img src="{{asset('images/adolescent.jpg')}}" style="height:300px"  alt="Kitten">
        <div class="tickmark"></div>
            <h2 style="color:black; font-weight:bold">FREE</h2>
        <p style="color:black">All dogs for adoption are considered as FREE for Adopters.</p>
    </div>
    </label>  
    <label class="option_item">
    <input type="checkbox" id="checkfee"  name="fee[]" value="Default" class="checkbox">
    <div class="option_inner junior">
        <img src="{{asset('images/adult.jpg')}}" style="height:300px"   alt="Junior">
        <div class="tickmark"></div> 
            <h2 style="color:black; font-weight:bold">DEFAULT</h2>
            <input type="number" class="form-control" id="fee" required placeholder="Adoption fee: P700.00" name="dogfee">
        <p style="color:black">Default adoption fee is customized as one fee that will be applicable for all dogs.</p>
    </div>
    </label>  
    <label class="option_item">
    <input type="checkbox"  name="fee[]" value="Custom" class="checkbox">
    <div class="option_inner prime">
        <img src="{{asset('images/senior.jpg')}}" style="height:300px"   alt="Prime">
        <div class="tickmark"></div>
            <h2 style="color:black; font-weight:bold">CUSTOM</h2>
        <p style="color:black">Custom adoption fee is applicable for customizing a desired fee for each dog.</p>
    </div>
    </label>  
    <label for=""></label>
    <label for=""></label>
    <div class="btn">
    <button type="submit">Finish</button>
    </div>
</main>  
</form>

@push('js')
<script type="text/javascript">
document.getElementById("fee").style.display = "none";
$(document).on('click', '#checkfee', function(){
   if(this.checked){
       document.getElementById("fee").style.display = "block";

   }
   else{
       document.getElementById("fee").style.display = "none";
   }
 });

</script>
@endpush