<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\AnimalShelter;
use App\Models\ValidDocuments;
use App\Models\PetOwner;
use App\Models\Subscription;
use App\Models\RejectedShelters;
use App\Models\SubscriptionTransac;
use App\Models\UploadedPhotos;
use App\Models\Feedback;
use App\Models\Usertype;
use App\Models\Adopter;
use App\Models\Adoption;
use App\Models\Animals;
use App\Models\Receipt;
use App\Models\AdoptionPayment;
use App\Models\Adopter_Notif;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Mail\ApproveShelter;
use App\Mail\ApprovePetOwner;
use App\Mail\RejectShelter;
use App\Mail\ApproveReactivation;
use App\Mail\ApproveReactivationPetOwner;
use App\Notifications\ValidDocumentsApprove;
use App\Notifications\ValidPetOwnerDocumentsApprove;
use App\Notifications\ValidDocumentsRejected;
use App\Notifications\ConfirmReactivationNotif;
use App\Notifications\ConfirmReactivationPetOwnerNotif;
use App\Notifications\ApproveProofPayment;
use App\Notifications\ApproveProofPaymentPetowner;
use App\Notifications\RejectedProof;
use App\Notifications\RejectProofPetowner;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\File; 
use Carbon\Carbon;
use App\Helpers\Helper;


class AdminController extends Controller
{
    function dashboard(){
        $admin=Admin::where('id','=',session('LoggedUserAdmin'))->first();
        $data = array(
            'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(), 
            'countshelter'=>AnimalShelter::where('is_verified_account','1')->where('is_verified_shelter','1')->count(),
            'countpetowner'=>PetOwner::where('is_verified_account','1')->where('is_verified_petowner','1')->count(),
            'countadopter'=>Adopter::where('email_verified_at','1')->count(),
            'revenue'=>$admin->revenue
          );
        return view('Admin.AdminDash', $data);
    }

    function approveadopterrequest($id){
      $adopter = Adopter::find($id);
      $adopter->status = 'isActive';
      $adopter->reactivation_request = 'noRequest';
      $adopter->update();

      $notif = new Adopter_Notif;
      $notif->notif_type = 'Account Reactivation';
      $notif->notif_from = 'Admin';
      $notif->notif_to = $id;
      $notif->notif_message = 'has reactivated your account';
      $notif->save();

      return redirect()->back()->with('status','Account has been reactivated');
    }

    function ViewAnimalShelters(){
        $admin=Admin::where('id','=',session('LoggedUserAdmin'))->first();
        $sheltercheck = AnimalShelter:: where(function($query){
          $query-> where('is_verified_shelter', '0')
                  ->orWhere('is_verified_shelter','2');
                  })
                  ->where('grace','!=','0')
                  ->pluck('id')
                  ->toArray();
        $shelter= AnimalShelter::
                      whereHas('shelterPhoto',function($query)use($sheltercheck){
                        $query->where('shelter_id',$sheltercheck);
                      })
                      ->get();
                      
        $data = array(
            'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
            'shelter' => $shelter
          );
        return view('Admin.Animal-Shelter.ViewShelter', $data);
    }

    function ViewAdopterRequest(){
        $data = array(
          'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
          'adopters' => Adopter::all()->where('reactivation_request','sentRequest'),
        );
      return view('Admin.Adopter.ReactivationRequest', $data);
  }

    function ViewSubscription(){
      $admin=Admin::where('id','=',session('LoggedUserAdmin'))->first();
      $subscription = Subscription::all();
      //dd($subscription);
        $data = array(
            'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
            'subscription' => Subscription::all(),
          );
        return view('Admin.Subscription.Viewsubscription', $data);
    }

    function Viewaddsubscription(){
      $admin=Admin::where('id','=',session('LoggedUserAdmin'))->first();
      $data = array(
          'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
        );
      return view('Admin.Subscription.addsubscription', $data);
    }

    function Vieweditsubscription($id){
      $admin=Admin::where('id','=',session('LoggedUserAdmin'))->first();
      $data = array(
          'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
          'subscription' => Subscription::find($id),
        );
      return view('Admin.Subscription.editsubscription', $data);
    }

    function savesubscription(Request $req){
      $check = Subscription::where('sub_name',$req->name)->count();
      if($check > 0){
        echo 'exist'; 
      }
      else{
        $sub = new Subscription;
        $sub->sub_name = $req->name;
        $sub->sub_span = $req->span;
        $sub->sub_price = $req->price;
        $sub->sub_span_type = $req->length;
        $sub->sub_credit = $req->credits;
        $sub->sub_desc = $req->desc;
  
        $sub->save();
        echo 'ok'; 
      }
    }

    function loadsubscription($id){
      $sub = Subscription::find($id);

      foreach($sub->sub_desc as $key =>$subs){
        $html = '<tr class="rowid">';
        $html .='<td><input type="text" name="desc[]" value="'.$subs.'" id="sub" class="form-control desc" /></td>';
        $html .='<td style="text-align:center"><button type="button" class="btn btn-danger btn-sm remove"><i class="fas fa-minus"></i></button></td>';
        $html .='</tr>';
        echo $html;
      }
    }

    function loadsubscriptionremove(Request $req,$id){
      $remove = Subscription::find($id);
      $desc = $remove->sub_desc;
      $index = array_search($req->get('name'), $desc);
      unset($desc[$index]); 
      $remove->sub_desc = $desc;
      $remove->update(); 
      echo 'ok';
    }

    function updatesubscription(Request $req,$id){
      $update = Subscription::find($id);
      $update->sub_name = $req->name;
      $update->sub_desc = $req->desc;
      $update->sub_price = $req->price;
      $update->sub_span = $req->span;
      $update->sub_span_type = $req->length;
      $update->sub_credit = $req->credits;
      $update->update();
      echo 'ok'; 
    }

    function deletesubscription($id){
      $checkactive = SubscriptionTransac::where('sub_id',$id)->count();
      if($checkactive > 0){
        return redirect()->back()->with('status1','Subscription is active at the moment');
      }
      else{
        $sub = Subscription::find($id);
        $sub->delete();
        return redirect()->back()->with('status','Removed Successfully');
      }
    }

    function ViewPetOwners(){
      $admin=Admin::where('id','=',session('LoggedUserAdmin'))->first();
      $petownercheck = PetOwner:: where(function($query){
        $query-> where('is_verified_petowner', '0')
                ->orWhere('is_verified_petowner','2');
                })
                ->where('grace','!=','0')
                ->pluck('id')
                ->toArray();
      $petowner= PetOwner::
                    whereHas('petownerPhoto',function($query)use($petownercheck){
                      $query->where('petowner_id',$petownercheck);
                    })
                    ->get();
      $data = array(
          'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
          'petowner' => $petowner,
        );
      return view('Admin.Pet-Owner.ViewPetOwner', $data);
    }

    function ViewPetOwnerDetails($petowner_id){
      $admin=Admin::where('id','=',session('LoggedUserAdmin'))->first();
     
      $data = array(
          'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
          'petowner' => PetOwner::find($petowner_id),
        );
      return view('Admin.Pet-Owner.ViewPetOwnerValidDocs', $data);
  }

    function ViewAnimalSheltersDetails($shelter_id){
        $admin=Admin::where('id','=',session('LoggedUserAdmin'))->first();
       
        $data = array(
            'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
            'shelter' =>  $shelter = Animalshelter::find($shelter_id),
          );
        return view('Admin.Animal-Shelter.ViewShelterValidDocs', $data);
    }

    function ViewPetOwnerEnlargeDetails($filename,$petowner_id){
      $admin=Admin::where('id','=',session('LoggedUserAdmin'))->first();
      $doc = DB::select("select filename from valid_docu where filename = '$filename'");
      //dd($phpWord);
        $data = array(
            'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
            'docs' => ValidDocuments::where('filename',$filename)->where('petowner_id',$petowner_id)->get(),
        );
     
        return view('Admin.Pet-Owner.ViewEnlargeFile', $data);
    }
    function ViewAnimalSheltersEnlargeDetails($filename , $shelter_id){
      $admin=Admin::where('id','=',session('LoggedUserAdmin'))->first();
      $doc = DB::select("select filename from valid_docu where filename = '$filename'");
      //dd($phpWord);
        $data = array(
            'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
            'docs' => DB::select("select *from valid_docu where filename = '$filename'"),
        );
     
        return view('Admin.Animal-Shelter.ViewEnlargeFile', $data);
    }

    function ApprovePetOwnerApp($petowner_id){
      $admin=Admin::where('id','=',session('LoggedUserAdmin'))->first();
      $petowner = PetOwner::find($petowner_id);
      $petowner->is_verified_petowner = "1";
      $petowner->save();

      $approve = array();
            $approve = [
                'petowner_id' =>$petowner->id,
                'petowner_name' => $petowner->fname,
            ];
      Mail::to($petowner->email)->send(new ApprovePetOwner($approve));

      $valid = array();
      $valid = [
          'approved' => 'Admin has approved your application,',
          'check' =>' please check your email'
      ];
      PetOwner::find($petowner->id)->notify( new ValidPetOwnerDocumentsApprove($valid));
      $data = array(
          'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
          'petowner' => PetOwner::all()->where('is_verified_petowner','0'),
        );
      return redirect(route('viewpetowner'))->with('status', 'Approved Successfully');
    }

    function ApproveShelterApp($shelter_id){
      $admin=Admin::where('id','=',session('LoggedUserAdmin'))->first();
      $shelter = AnimalShelter::find($shelter_id);
      $shelter->is_verified_shelter = "1";
      $shelter->save();

      $approve = array();
            $approve = [
                'shelter_id' =>$shelter->id,
                'shelter_name' => $shelter->shelter_name,
            ];
      Mail::to($shelter->email)->send(new ApproveShelter($approve));

      $valid = array();
      $valid = [
          'approved' => 'Admin has approved your application,',
          'check' =>' please check your email'
      ]; 
      AnimalShelter::find($shelter->id)->notify( new ValidDocumentsApprove($valid));
      $data = array(
          'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
          'shelter' => Animalshelter::all()->where('is_verified_shelter','0'),
        );
      return redirect(route('viewshelter'))->with('status', 'Approved Successfully');
    }

    function RejectPetOwnerApp(Request $req,$petowner_id){
      $admin = Admin::where('id','=',session('LoggedUserAdmin'))->first();
      $petowner = PetOwner::find($petowner_id);
      
      $petowner->is_verified_petowner = "2";
      $petowner->save();

      $feedback = new Feedback;
      $feedback->message = $req->feedback;
      $feedback->sender = 1;
      $feedback->owner_id = $petowner->id;
      $feedback->owner_type = 3; 
      $feedback->save();

      DB::table('valid_docu')->where('petowner_id', $petowner->id)->delete();
      $destination = 'uploads/valid-documents/'.$petowner->filename;
          if(File::exists($destination)){ 
              File::delete($destination);
          }   

      // $rejectedshelter = new RejectedShelters;
      // $rejectedshelter->shelter_name = $shelter->shelter_name;
      // $rejectedshelter->email = $shelter->email;
      // $rejectedshelter->address = $shelter->address;
      // $rejectedshelter->contactperson = $shelter->founder_name;
      // $rejectedshelter->contactno = $shelter->contact; 
      // $rejectedshelter->save();

      // $reject = array();
      //       $reject = [
      //           'petowner_id' =>$petowner->id,
      //           'petowner_name' => $petowner->fname,
      //       ];

      // Mail::to($shelter->email)->send(new RejectShelter($reject));

      // $rejected = array();
      // $rejected = [
      //     'check' => 'Please check your email ',
      //     'reject' =>' The system has detected...'
      // ];
      $rejected = array();
      $rejected = [
          'check' => 'It seems you have uploaded a wrong screenshot for the valid documents ',
          'reject' =>' you still have '.$petowner->grace.' chances left upon uploading the right documents'
      ];
      PetOwner::find($petowner->id)->notify( new ValidDocumentsRejected($rejected));
      return redirect(route('viewpetowner'))->with('status', 'Rejected Successfully');
    }

    function RejectShelterApp(Request $req,$shelter_id){
      
      $admin = Admin::where('id','=',session('LoggedUserAdmin'))->first();
      $shelter = AnimalShelter::find($shelter_id);
      
      $shelter->is_verified_shelter = "2";
      $shelter->save();

      $feedback = new Feedback;
      $feedback->message = $req->feedback;
      $feedback->sender = 1;
      $feedback->owner_id = $shelter->id;
      $feedback->owner_type = 2; 
      $feedback->save();

      DB::table('valid_docu')->where('shelter_id', $shelter->id)->delete();
      $destination = 'uploads/valid-documents/'.$shelter->filename;
          if(File::exists($destination)){ 
              File::delete($destination);
          }   

      // $reject = array();
      //       $reject = [
      //           'shelter_id' =>$shelter->id,
      //           'shelter_name' => $shelter->shelter_name,
      //       ];
      // Mail::to($shelter->email)->send(new RejectShelter($reject));

      $rejected = array();
      $rejected = [
          'check' => 'It seems you have uploaded a wrong screenshot for the valid documents ',
          'reject' =>' you still have '.$shelter->grace.' chances left upon uploading the right documents'
      ];
      AnimalShelter::find($shelter->id)->notify( new ValidDocumentsRejected($rejected));
      return redirect(route('viewshelter'))->with('status', 'Rejected Successfully');
    }

    function reject_remove_shelter($shelter_id){
      if(session('LoggedUser') == $shelter_id){
        session()->pull('LoggedUser');

        $shelter = AnimalShelter::find($shelter_id);
        $shelter->delete();

        return redirect('/User/login')->with('stat','Your account has been removed!');
      }
    }

    function reject_remove_petowner($petowner_id){
      if(session('LoggedUserPet') == $petowner_id){
        session()->pull('LoggedUserPet');

        $petowner = PetOwner::find($petowner_id);
        $petowner->delete();

        return redirect('/User/login')->with('stat','Your account has been removed!');
      }
    }

    function viewReactivation(){
      $data = array(
        'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
        'shelter' => Animalshelter::all()->where('is_verified_activation','2'),
      );
      return view('Admin.Animal-Shelter.ViewShelterReactivation',$data);
    }

    function viewReactivationpetowner(){
      $data = array(
        'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
        'petowner' => PetOwner::all()->where('is_verified_activation','2'),
      );
      return view('Admin.Pet-Owner.ViewShelterReactivation',$data);
    }

    function approveAll(){
      $checkshelter = AnimalShelter::where('is_verified_activation','=','2')->count();
      if($checkshelter > 0){
        $shelter = AnimalShelter::where('is_verified_activation','=','2');
        $shelterall = DB::select("select *from animal_shelters where is_verified_activation = '2'");
        $emailquery = array();
        $emailquery[]= $shelterall;
        //dd($emailquery);
  
        foreach($shelterall as $shelters){
          $approvedrequest = array();
          $approvedrequest = [
              'reactivated' => 'Your account has been reactivated, ',
              'check' => 'please check your email for confirmation'
          ];
          AnimalShelter::find($shelters->id)->notify( new ConfirmReactivationNotif($approvedrequest));
  
          $email = array();
          $email = [
              'shelter_id' =>$shelters->id,
              'shelter_name' => $shelters->shelter_name,
          ];
          Mail::to($shelters->email)->send(new ApproveReactivation($email));
        }
        $shelter->update(['is_verified_activation'=>'0']);
        return redirect()->back()->with('status','All requests have been approved');
      }
      else{
        return redirect()->back()->with('status1','No reactivation requests found');
      }
    }

    function approveAllpetowner(){
      $checkpetowner = PetOwner::where('is_verified_activation','=','2')->count();
      if($checkpetowner > 0){
        $petowner = PetOwner::where('is_verified_activation','=','2');
        $petownerall = DB::select("select *from pet_owners where is_verified_activation = '2'");
        $emailquery = array();
        $emailquery[]= $petownerall;
        //dd($emailquery);
  
        foreach($petownerall as $petowners){
          $approvedrequest = array();
          $approvedrequest = [
              'reactivated' => 'Your account has been reactivated, ',
              'check' => 'please check your email for confirmation'
          ];
          PetOwner::find($petowners->id)->notify( new ConfirmReactivationPetOwnerNotif($approvedrequest));
  
          $email = array();
          $email = [
              'shelter_id' =>$petowners->id,
              'shelter_name' => $petowners->fname,
          ];
          Mail::to($petowners->email)->send(new ApproveReactivationPetOwner($email));
        }
        $petowner->update(['is_verified_activation'=>'0']);
        return redirect()->back()->with('status','All requests have been approved');
      }
      else {
        return redirect()->back()->with('status1','No reactivation requests found');
      }
    
    }

    function reactivation($shelter_id){
      $shelter = AnimalShelter::find($shelter_id);
      $shelter->is_verified_activation = "0";
      $shelter->save();

      $approvedrequest = array();
      $approvedrequest = [
          'reactivated' => 'Your account has been reactivated, ',
          'check' => 'please check your email for confirmation'
      ];
      AnimalShelter::find($shelter->id)->notify( new ConfirmReactivationNotif($approvedrequest));

      $email = array();
      $email = [
          'shelter_id' =>$shelter->id,
          'shelter_name' => $shelter->shelter_name,
      ];
      Mail::to($shelter->email)->send(new ApproveReactivation($email));


      return redirect()->back()->with('status','Request approved');
    }

    function reactivationpetowner($petowner_id){
      $petowner = PetOwner::find($petowner_id);
      $petowner->is_verified_activation = "0";
      $petowner->save();

      $approvedrequest = array();
      $approvedrequest = [
          'reactivated' => 'Your account has been reactivated, ',
          'check' => 'please check your email for confirmation'
      ];
      PetOwner::find($petowner->id)->notify( new ConfirmReactivationPetOwnerNotif($approvedrequest));

      $email = array();
      $email = [
          'petowner_id' =>$petowner->id,
          'petowner_name' => $petowner->fname,
      ];
      Mail::to($petowner->email)->send(new ApproveReactivationPetOwner($email));
      
      return redirect()->back()->with('status','Request approved');
    }

    function viewproofpayment(){
      $data = array(
        'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
        'subscriptions'=>SubscriptionTransac::all()->where('status','pending'),
      );  
      return view('Admin.Transaction.subscription_transac',$data);
    }

    function viewenlargeproofpayment($sub_id, $user_id){
        $check = SubscriptionTransac::where('status','pending')->where('sub_id',$sub_id)->where('shelter_id',$user_id)->count();
        if($check > 0){
            $data = array(
              'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
              'proof' => UploadedPhotos::where('sub_id',$sub_id)->where('shelter_id',$user_id)->get(),
          );
          return view('Admin.Transaction.ViewEnlargeFile', $data);
        }
        else{
          $data = array(
            'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
            'proof' => UploadedPhotos::where('sub_id',$sub_id)->where('petowner_id',$user_id)->get(),
          );
        return view('Admin.Transaction.ViewEnlargeFile', $data);
      }
    }
    function approveproofpayment($sub_id,$user_id){
      $check = SubscriptionTransac::where('status','pending')->where('sub_id',$sub_id)->where('shelter_id',$user_id)->count();

      if($check > 0){
        $shelter = SubscriptionTransac::where('status','pending')->where('sub_id',$sub_id)->where('shelter_id',$user_id)->first();

        $approvedproof = [
          'shelter_name' => 'You have successfully subscribed '.$shelter->subscription->sub_name.' promo',
          'promo' => ' valid for '.$shelter->subscription->sub_span.''.$shelter->subscription->sub_span_type.'/s',
      ];
      $shelter->status = 'approved';
      $shelter->update();
      AnimalShelter::find($user_id)->notify(new ApproveProofPayment($approvedproof));

      $subscription = Subscription::find($sub_id);
      $feedback = Feedback::where('sub_id',$subscription->id)->delete();
       
        $proofphoto = UploadedPhotos::where('sub_id',$sub_id)->where('shelter_id',$user_id)->get();
        foreach($proofphoto as $pic){
            $destination = 'uploads/animal-shelter/uploaded-photos/'.$pic->imagename;
            if(File::exists($destination)){ 
              File::delete($destination);
            }   
            $pic->delete();
        }
          $data = array(
            'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
            'proof' => UploadedPhotos::where('sub_id',$sub_id)->where('shelter_id',$user_id)->get(),
        );
        //check if credits is 0
        $credits = AnimalShelter::find($user_id);
        if($credits->TotalCredits == "0"){
          $credits->TotalCredits = $subscription->sub_credit;
          $credits->update();
           //getting the expiry date && the subscription span
           $span_type = $subscription->sub_span_type;
           if($span_type == "day"){
             $convertspan = (int)$subscription->sub_span;
             $datestart = Carbon::parse($shelter->updated_at);  
             $expiry = $datestart->addDays($convertspan);
             //update
             $shelter->expiry_date = Carbon::parse($expiry)->format('F d, Y h:i:s A');  
             $shelter->update();
           }
           elseif($span_type == "month"){
             $convertspan = (int)$subscription->sub_span;
             $datestart = Carbon::parse($shelter->updated_at);  
             $expiry = $datestart->addMonths($convertspan);
             //update
             $shelter->expiry_date = Carbon::parse($expiry)->format('F d, Y h:i:s A');  
             $shelter->update();
           }
           elseif($span_type == "year"){
             $convertspan = (int)$subscription->sub_span;
             $datestart = Carbon::parse($shelter->updated_at);  
             $expiry = $datestart->addYears($convertspan);
             //update
             $shelter->expiry_date = Carbon::parse($expiry)->format('F d, Y h:i:s A');  
             $shelter->update();
           }
        }
        else{
          if($subscription->sub_credit == "UNLI"){
            $credits->TotalCredits = "UNLI";
            $credits->update();
            //getting the expiry date && the subscription span
            $span_type = $subscription->sub_span_type;
            if($span_type == "day"){
              $convertspan = (int)$subscription->sub_span;
              $datestart = Carbon::parse($shelter->updated_at);  
              $expiry = $datestart->addDays($convertspan);
              //update
              $shelter->expiry_date = Carbon::parse($expiry)->format('F d, Y h:i:s A');  
              $shelter->update();
            }
            elseif($span_type == "month"){
              $convertspan = (int)$subscription->sub_span;
              $datestart = Carbon::parse($shelter->updated_at);  
              $expiry = $datestart->addMonths($convertspan);
              //update
              $shelter->expiry_date = Carbon::parse($expiry)->format('F d, Y h:i:s A');  
              $shelter->update();
            }
            elseif($span_type == "year"){
              $convertspan = (int)$subscription->sub_span;
              $datestart = Carbon::parse($shelter->updated_at);  
              $expiry = $datestart->addYears($convertspan);
              //update
              $shelter->expiry_date = Carbon::parse($expiry)->format('F d, Y h:i:s A');  
              $shelter->update();
            }
          }
          else{
            $credit = (int)$subscription->sub_credit;
            $subtotal = (int)$credits->TotalCredits;
            $total = $credit + $subtotal;
            $credits->TotalCredits = $total;
            $credits->update();

            $span_type = $subscription->sub_span_type;
            if($span_type == "day"){
              $convertspan = (int)$subscription->sub_span;
              $datestart = Carbon::parse($shelter->updated_at);  
              $expiry = $datestart->addDays($convertspan);
              //update
              $shelter->expiry_date = Carbon::parse($expiry)->format('F d Y h:i:s A');  
              $shelter->update();
            }
            elseif($span_type == "month"){
              $convertspan = (int)$subscription->sub_span;
              $datestart = Carbon::parse($shelter->updated_at);  
              $expiry = $datestart->addMonths($convertspan);
              //update
              $shelter->expiry_date = Carbon::parse($expiry)->format('F d Y h:i:s A');  
              $shelter->update();
            }
            elseif($span_type == "year"){
              $convertspan = (int)$subscription->sub_span;
              $datestart = Carbon::parse($shelter->updated_at);  
              $expiry = $datestart->addYears($convertspan);
              //update
              $shelter->expiry_date = Carbon::parse($expiry)->format('F d Y h:i:s A');  
              $shelter->update();
            }
          }
        }
        return redirect()->back()->with('status','Approved Successfully');
      }
      if($check == 0){
        $petowner = SubscriptionTransac::where('status','pending')->where('sub_id',$sub_id)->where('petowner_id',$user_id)->first();
        
        $approvedproof = [
          'petowner_name' => 'You have successfully subscribed '.$petowner->subscription->sub_name.' promo',
          'promo' => ' valid for '.$petowner->subscription->sub_span.''.$petowner->subscription->sub_span_type.'/s',
        ];
        PetOwner::find($user_id)->notify(new ApproveProofPaymentPetowner($approvedproof));

        $petowner->status = 'approved';
        $petowner->update();
        
        $subscription = Subscription::find($sub_id);
        $feedback = Feedback::where('sub_id',$subscription->id)->delete();

        $proofphoto = UploadedPhotos::where('sub_id',$sub_id)->where('petowner_id',$user_id)->get();
        foreach($proofphoto as $pic){
            $destination = 'uploads/pet-owner/uploaded-photos/'.$pic->imagename;
            if(File::exists($destination)){ 
              File::delete($destination);
            }   
            $pic->delete();
        }
        $data = array(
          'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
          'proof' => UploadedPhotos::where('sub_id',$sub_id)->where('petowner_id',$user_id)->get(),
        );
        //check if credits is 0
        $credits = PetOwner::find($user_id);
        if($credits->TotalCredits == "0"){
          $credits->TotalCredits = $subscription->sub_credit;
          $credits->update();
              //getting the expiry date && the subscription span
              $span_type = $subscription->sub_span_type;
              if($span_type == "day"){
                $convertspan = (int)$subscription->sub_span;
                $datestart = Carbon::parse($petowner->updated_at);  
                $expiry = $datestart->addDays($convertspan);
                //update
                $petowner->expiry_date = Carbon::parse($expiry)->format('F d, Y h:i:s A');  
                $petowner->update();
              }
              elseif($span_type == "month"){
                $convertspan = (int)$subscription->sub_span;
                $datestart = Carbon::parse($petowner->updated_at);  
                $expiry = $datestart->addMonths($convertspan);
                //update
                $petowner->expiry_date = Carbon::parse($expiry)->format('F d, Y h:i:s A');  
                $petowner->update();
              }
              elseif($span_type == "year"){
                $convertspan = (int)$subscription->sub_span;
                $datestart = Carbon::parse($petowner->updated_at);  
                $expiry = $datestart->addYears($convertspan);
                //updates
                $petowner->expiry_date = Carbon::parse($expiry)->format('F d, Y h:i:s A');  
                $petowner->update();
              }
        }
        else{
          if($subscription->sub_credit == "UNLI"){
            $credits->TotalCredits = "UNLI";
            $credits->update();
             //getting the expiry date && the subscription span
           $span_type = $subscription->sub_span_type;
           if($span_type == "day"){
             $convertspan = (int)$subscription->sub_span;
             $datestart = Carbon::parse($petowner->updated_at);  
             $expiry = $datestart->addDays($convertspan);
             //update
             $petowner->expiry_date = Carbon::parse($expiry)->format('F d, Y h:i:s A');  
             $petowner->update();
           }
           elseif($span_type == "month"){
             $convertspan = (int)$subscription->sub_span;
             $datestart = Carbon::parse($petowner->updated_at);  
             $expiry = $datestart->addMonths($convertspan);
             //update
             $petowner->expiry_date = Carbon::parse($expiry)->format('F d, Y h:i:s A');  
             $petowner->update();
           }
           elseif($span_type == "year"){
             $convertspan = (int)$subscription->sub_span;
             $datestart = Carbon::parse($petowner->updated_at);  
             $expiry = $datestart->addYears($convertspan);
             //updates
             $petowner->expiry_date = Carbon::parse($expiry)->format('F d, Y h:i:s A');  
             $petowner->update();
           }
          }
          else{
            $credit = (int)$subscription->sub_credit;
            $subtotal = (int)$credits->TotalCredits;
            $total = $credit + $subtotal;
            $credits->TotalCredits = $total;
            $credits->update();
             //getting the expiry date && the subscription span
           $span_type = $subscription->sub_span_type;
           if($span_type == "day"){
             $convertspan = (int)$subscription->sub_span;
             $datestart = Carbon::parse($petowner->updated_at);  
             $expiry = $datestart->addDays($convertspan);
             //update
             $petowner->expiry_date = Carbon::parse($expiry)->format('F d, Y h:i:s A');  
             $petowner->update();
           }
           elseif($span_type == "month"){
             $convertspan = (int)$subscription->sub_span;
             $datestart = Carbon::parse($petowner->updated_at);  
             $expiry = $datestart->addMonths($convertspan);
             //update
             $petowner->expiry_date = Carbon::parse($expiry)->format('F d, Y h:i:s A');  
             $petowner->update();
           }
           elseif($span_type == "year"){
             $convertspan = (int)$subscription->sub_span;
             $datestart = Carbon::parse($petowner->updated_at);  
             $expiry = $datestart->addYears($convertspan);
             //update
             $petowner->expiry_date = Carbon::parse($expiry)->format('F d, Y h:i:s A');  
             $petowner->update();
           }
          }
        }
        return redirect()->back()->with('status','Approved Successfully');
      }
    }
    function rejectproofpayment($sub_id,$user_id){
      $check = SubscriptionTransac::where('status','pending')->where('sub_id',$sub_id)->where('shelter_id',$user_id)->count();
      if($check > 0){
          $data = array(
            'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
            'proof' => UploadedPhotos::where('sub_id',$sub_id)->where('shelter_id',$user_id)->get(),
        );
        return view('Admin.Transaction.ViewEnlargeFile', $data);
      }
      else{
        $data = array(
          'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
          'proof' => UploadedPhotos::where('sub_id',$sub_id)->where('petowner_id',$user_id)->get(),
        );
      return view('Admin.Transaction.ViewEnlargeFile', $data);
      }
    } 
    function feedback(Request $req, $sub_id, $receiver){
      $checkshelter = AnimalShelter::where('shelter_name',$receiver)->count();
       if($checkshelter > 0){
        $bind = AnimalShelter::where('shelter_name',$receiver)->first();
        $user = Usertype::where('id',2)->first();
        $admin = Usertype::where('usertype','Admin')->first();
        $feedback = new Feedback;
        $feedback->message = $req->feedback;
        $feedback->sender = $admin->id;
        $feedback->owner_id = $bind->id;
        $feedback->owner_type = $user->id;
        $feedback->sub_id = $sub_id;
        $feedback->save();

        $proofphoto = UploadedPhotos::where('sub_id',$sub_id)->where('shelter_id',$bind->id)->get();
        foreach($proofphoto as $pic){
            $destination = 'uploads/animal-shelter/uploaded-photos/'.$pic->imagename;
            if(File::exists($destination)){ 
              File::delete($destination);
            }   
            $pic->delete();
        }
       
        $rejectedproof = [
          'payment' => 'There is something wrong upon the proof of payment you had sent,',
          'tryagain' => ' please upload again the neccessary proof of photo on your subscription',
        ];
        AnimalShelter::find($bind->id)->notify(new RejectedProof($rejectedproof));

        $transac = SubscriptionTransac::where('status','pending')->where('sub_id',$sub_id)->where('shelter_id',$bind->id)->first();
        $transac->status = "not approved";
        $transac->update();

        return redirect()->back()->with('status','Feedback Sent Successfully');
      }  
      if($checkshelter == 0){
        $bind = PetOwner::where('email',$receiver)->first();
        $user = Usertype::where('id',3)->first();
        $admin = Usertype::where('usertype','Admin')->first();
        $feedback = new Feedback;
        $feedback->message = $req->feedback;
        $feedback->sender = $admin->id;
        $feedback->owner_id = $bind->id;
        $feedback->owner_type = $user->id;
        $feedback->sub_id = $sub_id;
        $feedback->save();

        $proofphoto = UploadedPhotos::where('sub_id',$sub_id)->where('petowner_id',$bind->id)->get();
        foreach($proofphoto as $pic){
            $destination = 'uploads/pet-owner/uploaded-photos/'.$pic->imagename;
            if(File::exists($destination)){ 
              File::delete($destination);
            }   
            $pic->delete();
        }
        $rejectedproof = [
          'payment' => 'There is something wrong upon the proof of payment you had sent,',
          'tryagain' => ' please upload again the neccessary proof of photo on your subscription',
        ];
        PetOwner::find($bind->id)->notify(new RejectProofPetowner($rejectedproof));

        $transac = SubscriptionTransac::where('status','pending')->where('sub_id',$sub_id)->where('petowner_id',$bind->id)->first();
        $transac->status = "not approved";
        $transac->update();

        return redirect()->back()->with('status','Feedback Sent Successfully');
      }
    }
    function adoptionpayment(){
      $data = array(
        'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
        'payments'=>AdoptionPayment::all()->where('status','pending'),
      );  
      return view('Admin.Transaction.Payment',$data);
    }
 
    function enlargeadoption($id){
      $data =array(
          'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
          'images'=>AdoptionPayment::find($id),
      );
      return view('Admin.Transaction.enlargepayment',$data);
    }

    function approveadoptionpayment(Request $req, $id){
      $check = AdoptionPayment::find($id);
      $check->status = 'approved';
      $check->feedback = $req->feedback;
      $check->update();
      //notification
      $notif = new Adopter_Notif;
      $notif->notif_type = "Adoption Payment";
      $notif->notif_from ="Admin";
      $notif->notif_to = $check->adopter_id;
      $notif->notif_message = " has approved your adoption payment";
      $notif->save();
      //saving in receipt
      $slip = Helper::IDGenerator(new Receipt, 'receipt_no', 5, 'RPT');
      $receipt = new Receipt;
      $receipt->adopter_id = $check->adopter_id;
      $receipt->animal_id = $check->animal_id;
      $receipt->usertype_id = $check->owner_type;
      if($check->owner_type == 2){
        $getid = Animals::where('id',$check->animal_id)->first();
        $receipt->owner_id = $getid->shelter->id;
      }
      elseif($check->owner_type == 3){
        $getid = Animals::where('id',$check->animal_id)->first();
        $receipt->owner_id = $getid->petowner->id;
      }
      $receipt->adoption_id = $check->adoption_id;
      $receipt->payment_id =$check->id;
      $receipt->receipt_no = $slip;
      $receipt->status = "pending";
      $receipt->save();
      //Animals
      $animal = Animals::find($check->animal_id);
      $animal->status = "Adopted";
      $animal->update();
      return redirect()->back()->with('status','Approval feedback sent successfully');
    }

    function rejectadoptionpayment(Request $req, $id){
      $check = AdoptionPayment::find($id);
      $check->status = 'not approved';
      $check->feedback = $req->feedback;
      $check->update();
      //get animal and adoption
      $adoption = Adoption::where('id',$check->adoption_id)->first();
      $adoption->paymentflag = "0";
      $adoption->update();
      //notification
      $notif = new Adopter_Notif;
      $notif->notif_type = "Adoption Payment";
      $notif->notif_from ="Admin";
      $notif->notif_to = $check->adopter_id;
      $notif->notif_message = " has disapproved your adoption payment, please check thoroughly on the photo you had sent.";
      $notif->save();
      return redirect()->back()->with('status','Disapproval feedback sent successfully');
    } 

    function viewtransfer(){
      $checkfree = Animals::where('status','Adopted')->where('fee','FREE')->pluck('id')->toArray();
      $data = array(
        'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
        'transfer'=>Receipt::whereNotIn('animal_id',$checkfree)->where('process','confirmed')->where('status','received')->get(),
      );
      return view('Admin.Transfer.viewtransfer',$data);
    }

    function transfermoney($id){
      $data = array(
        'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
        'transfer'=>Receipt::find($id)
      );
      return view('Admin.Transfer.paypal',$data);
    }
    function transferring($id){
      $receipt = Receipt::find($id);
      if($receipt->usertype_id == 2){
        $shelter = AnimalShelter::where('id',$receipt->owner_id)->first();
        if($shelter->revenue == "0"){
          $shelter->revenue = $receipt->animal->fee;
          $shelter->update();
          $transferred = [
            'payment' => 'You have received PHP'.$receipt->animal->fee,
            'tryagain' => ' from the adoption fee remitted by '.$receipt->adopter->fname.' '.$receipt->adopter->lname,
          ];
          AnimalShelter::find($shelter->id)->notify(new RejectedProof($transferred));
          $transferred = [
            'payment' => 'You have successfully transferred PHP'.$receipt->animal->fee,
            'tryagain' => ' in '.$receipt->shelter->shelter_name.' account.',
          ];
          Admin::find(1)->notify(new RejectedProof($transferred));
          $receipt->process = 'completed';
          $receipt->update();
        }
        else{
            $subtotal = (int)$shelter->revenue;
            $fee = (int)$receipt->animal->fee;
            $total = $subtotal + $fee;
            $shelter->revenue = $total.'.00';
            $shelter->update();
            $transferred = [
              'payment' => 'You have received PHP'.$receipt->animal->fee,
              'tryagain' => ' from the adoption fee remitted by '.$receipt->adopter->fname.' '.$receipt->adopter->lname,
            ];
            AnimalShelter::find($shelter->id)->notify(new RejectedProof($transferred));
            $transferred = [
              'payment' => 'You have successfully transferred PHP'.$receipt->animal->fee,
              'tryagain' => ' in '.$receipt->shelter->shelter_name.' account.',
            ];
            Admin::find(1)->notify(new RejectedProof($transferred));
            $receipt->process = 'completed';
            $receipt->update();
        }
      }
      else{
        $petowner = PetOwner::where('id',$receipt->owner_id)->first();
        if($petowner->revenue == "0"){
          $petowner->revenue = $receipt->animal->fee;
          $petowner->update();
          $transferred = [
            'payment' => 'You have received PHP'.$receipt->animal->fee,
            'tryagain' => ' from the adoption fee remitted by '.$receipt->adopter->fname.' '.$receipt->adopter->lname,
          ];
          PetOwner::find($petowner->id)->notify(new RejectedProof($transferred));
          $transferred = [
            'payment' => 'You have successfully transferred PHP'.$receipt->animal->fee,
            'tryagain' => ' in '.$receipt->petowner->fname.' '.$receipt->petowner->lname.' account.',
          ];
          Admin::find(1)->notify(new RejectedProof($transferred));
          $receipt->process = 'completed';
          $receipt->update();
        }
        else{
            $subtotal = (int)$petowner->revenue;
            $fee = (int)$receipt->animal->fee;
            $total = $subtotal + $fee;
            $petowner->revenue = $total.'.00';
            $petowner->update();
            $transferred = [
              'payment' => 'You have received PHP'.$receipt->animal->fee,
              'tryagain' => ' from the adoption fee remitted by '.$receipt->adopter->fname.' '.$receipt->adopter->lname,
            ];
            PetOwner::find($petowner->id)->notify(new RejectedProof($transferred));
            $transferred = [
              'payment' => 'You have successfully transferred PHP'.$receipt->animal->fee,
              'tryagain' => ' in '.$receipt->petowner->fname.' '.$receipt->petowner->lname.' account.',
            ];
            Admin::find(1)->notify(new RejectedProof($transferred));
            $receipt->process = 'completed';
            $receipt->update();
        }
      }
    }
    
    function view_reports(){
      $data = array(
        'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
      );  
      return view('Admin.Reports.reports',$data);
    }
    function view_shelters(){
      $data = array(
        'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
        'shelters'=> AnimalShelter::where('is_verified_account','1')->where('is_verified_shelter','1')->get(),
      );  
      return view('Admin.Reports.listofshelter',$data);
    }
    function searchshelter(Request $req){
      $fromdate = $req->fromdate;
      $todate = $req->todate;
      $data =array(
          'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
          'shelters'=> AnimalShelter::where('is_verified_account','1')->where('is_verified_shelter','1')
          ->where('created_at','>=',$fromdate)->where('created_at','<=',$todate)->get()
      );
      return view('Admin.Reports.listofshelter',$data);
    }
    function searchpetowner(Request $req){
      $fromdate = $req->fromdate;
      $todate = $req->todate;
      $data =array(
          'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
          'petowners'=> PetOwner::where('is_verified_account','1')->where('is_verified_petowner','1')
          ->where('created_at','>=',$fromdate)->where('created_at','<=',$todate)->get()
      );
      return view('Admin.Reports.listofpetowner',$data);
    }
    function view_petowners(){
      $data = array(
        'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
        'petowners'=> PetOwner::where('is_verified_account','1')->where('is_verified_petowner','1')->get(),
      );  
      return view('Admin.Reports.listofpetowner',$data);
    }
    function searchadopter(Request $req){
      $fromdate = $req->fromdate;
      $todate = $req->todate;
      $data =array(
          'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
          'adopters'=> Adopter::where('email_verified_at','1')
          ->where('created_at','>=',$fromdate)->where('created_at','<=',$todate)->get()
      );
      return view('Admin.Reports.listofadopter',$data);
    }
    function view_adopters(){
      $data = array(
        'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
        'adopters'=> Adopter::where('email_verified_at','1')->get(),
      );  
      return view('Admin.Reports.listofadopter',$data);
    }
    function searchtransfer(Request $req){
      $checkfree = Animals::where('fee','FREE')->pluck('id')->toArray();
      $fromdate = $req->fromdate;
      $todate = $req->todate;
      $data =array(
          'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
          'transfers'=>Receipt::whereNotIn('animal_id',$checkfree)->where('process','completed')->where('status','received')
          ->where('updated_at','>=',$fromdate)->where('updated_at','<=',$todate)->get()
      );
      return view('Admin.Reports.transferpayment',$data);
    }
    function view_transfer(){
      $checkfree = Animals::where('fee','FREE')->pluck('id')->toArray();
      $data = array(
        'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
        'transfers'=> Receipt::whereNotIn('animal_id',$checkfree)->where('process','completed')->where('status','received')->get(),
      );  
      return view('Admin.Reports.transferpayment',$data);
    }
    function searchrevenue(Request $req){
      $fromdate = $req->fromdate;
      $todate = $req->todate;
      $data =array(
          'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
          'subscriptions'=>SubscriptionTransac::where('status','approved')
          ->where('created_at','>=',$fromdate)->where('created_at','<=',$todate)->get()
      );
      return view('Admin.Reports.revenue',$data);
    }
    function view_revenue(){
      $data = array(
        'admin' => Admin::where('id','=',session('LoggedUserAdmin'))->first(),
        'subscriptions'=> SubscriptionTransac::where('status','approved')->get(),
      );  
      return view('Admin.Reports.revenue',$data);
    }
} 
