
<h4> Congratulations {{$mail_data['shelter_name']}}! The system has approved your valid documents</h4>

<p>Please click below in order to 
proceed.</p>
<p>P.S After clicking, your account will automatically log out and try logging in again.</p>

<a href="{{url('/auto/logout/'.$mail_data['shelter_id'])}}"><button type="button" class="btn btn-primary">Click here!</button></a>
<br>
<br>

Best regards,
<br>
furescue team