<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AnimalShelterManagement;
use App\Http\Controllers\PetOwnerManagement;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\DropDownController;
use App\Models\Admin;
use App\Models\PetOwner;
use App\Models\AnimalShelter;
use Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/test/upload/petowner',[UploadController::class,'upload_petowner'])->name('up.upload.petowner');
Route::post('/SecondaryIntro/petowner',[PetOwnerManagement::class,'secondaryIntro'])->name('second.intro.petowner');
Route::post('/Dog/petowner',[PetOwnerManagement::class,'DogLifeStage'])->name('doglife.stage.petowner');
Route::post('/Cat/petowner',[PetOwnerManagement::class,'CatLifeStage'])->name('catlife.stage.petowner');
Route::post('/Both/petowner',[PetOwnerManagement::class,'BothLifeStage'])->name('bothlife.stage.petowner');
Route::post('/AddDogBreed/petowner',[PetOwnerManagement::class,'adddogbreed'])->name('dogbreed.add.petowners');
Route::post('/AddCatBreed/petowner',[PetOwnerManagement::class,'addcatbreed'])->name('catbreed.add.petowners');
Route::post('/AddBothBreed/petowner',[PetOwnerManagement::class,'addbothbreed'])->name('bothbreed.add.petowner');
Route::post('/SaveDogBreed/petowner',[PetOwnerManagement::class,'savedogbreed'])->name('dogbreed.save.petowner');
Route::post('/SaveCatBreed/petowner',[PetOwnerManagement::class,'savecatbreed'])->name('catbreed.save.petowner');
Route::post('/SaveBothBreed/petowner',[PetOwnerManagement::class,'savebothbreed'])->name('bothbreed.save.petowner');
Route::post('/DeleteDogBreed/petowner',[PetOwnerManagement::class,'deletedogbreed'])->name('dogbreed.delete.petowner');
Route::post('/DeleteCatBreed/petowner',[PetOwnerManagement::class,'deletecatbreed'])->name('catbreed.delete.petowner');
Route::post('/DeleteBothBreed/petowner',[PetOwnerManagement::class,'deletebothbreed'])->name('bothbreed.delete.petowner');
Route::post('/DogFee/petowner',[PetOwnerManagement::class,'DogFee'])->name('dog.fee.petowner');
Route::post('/CatFee/petowner',[PetOwnerManagement::class,'CatFee'])->name('cat.fee.petowner');
Route::post('/BothFee/petowner',[PetOwnerManagement::class,'BothFee'])->name('both.fee.petowner');
Route::post('/EditProfile/petowner/{id}',[PetOwnerManagement::class,'UpdateProfile']);
Route::post('/UpdatePassword/petowner/{id}',[PetOwnerManagement::class,'UpdatePassword']);
Route::post('/Profile/deactivate/petowner',[PetOwnerManagement::class,'DeactivateProfileAccess'])->name('deactivate.petowner');
Route::post('/Profile/deactivation/petowner',[PetOwnerManagement::class,'Deactivation'])->name('deactivation.account.petowner');
Route::post('/AnimalManagement/petowner',[PetOwnerManagement::class,'AddAnimal'])->name('addpet.petowner');
Route::post('/UpdatePet/{id}',[PetOwnerManagement::class,'UpdateAnimal'])->name('update.pet');
Route::post('/DeletePet/{id}',[PetOwnerManagement::class,'DeleteAnimal'])->name('delete.pet');
Route::post('/customselection/deletecat_breed/petowner/{id}',[DropDownController::class,'deletecatbreed_petowner']);
Route::post('/customselection/deletedog_breed/petowner/{id}',[DropDownController::class,'deletedogbreed_petowner']);
Route::post('/customselection/addcat_breed/petowner',[DropDownController::class,'addcat_breed_petowner'])->name('addcat.breed.petowner');
Route::post('/customselection/adddog_breed/petowner',[DropDownController::class,'adddog_breed_petowner'])->name('dogbreed.add.petowner');
Route::post('/customselection/selection_adoption_savefee/petowner',[DropDownController::class,'selection_adoption_savefee_petowner'])->name('selection.adoption.savefee.petowner');
Route::post('/AdoptionPolicy/petowner',[PetOwnerManagement::class,'AddPolicy'])->name('add.policy');
Route::post('/AdoptionPolicy/edit/petowner/{id}',[PetOwnerManagement::class,'EditPolicy'])->name('edit.policy');
Route::post('/AdoptionPolicy/delete/petowner/{id}',[PetOwnerManagement::class,'DeletePolicy'])->name('delete.policy');
Route::post('/vaccine/vac/petowner',[PetOwnerManagement::class,'AddVaccine'])->name('addvaccine.petowner');
Route::post('/vaccine/editvac/petowner/{id}',[PetOwnerManagement::class,'EditVaccine'])->name('editvaccine.petowner');
Route::post('/vaccine/deletevac/petowner/{id}',[PetOwnerManagement::class,'DeleteVaccine'])->name('deletevaccine.petowner');
Route::post('/deworm/editdeworm/petowner/{id}',[PetOwnerManagement::class,'EditDeworm'])->name('editdeworm.petowner');
Route::post('/deworm/deletedeworm/petowner/{id}',[PetOwnerManagement::class,'DeleteDeworm'])->name('deletedeworm.petowner');
Route::post('/vaccine/deworm/petowner',[PetOwnerManagement::class,'AddDeworm'])->name('adddeworm.petowner');
Route::post('/AllocationVaccine/petowner/{id}/{vac_id}',[PetOwnerManagement::class,'Allocation_Vaccine']);
Route::post('/AllocationDeworm/petowner/{id}/{dew_id}',[PetOwnerManagement::class,'Allocation_Deworm']);
Route::post('/postpet/uploadphoto/petowner/{id}',[UploadController::class,'uploadphotopost_petowner'])->name('post.uploadphoto.petowner');
Route::post('/postpet/postsave/petowner/{id}',[PetOwnerManagement::class,'post_pet_save'])->name('post.pet.save.petowner');
Route::post('/postpet/postupdate/petowner/{id}',[PetOwnerManagement::class,'post_pet_update'])->name('post.pet.update.petowner');

Route::post('/Admin/savesubscription',[AdminController::class,'savesubscription'])->name('save.subscription');
Route::post('/Admin/updatesubscription/{id}',[AdminController::class,'updatesubscription'])->name('update.subscription');
Route::post('/Admin/deletesubscription/{id}',[AdminController::class,'deletesubscription'])->name('delete.subscription');
Route::post('/Admin/uploadproof/{id}',[UploadController::class,'uploadproof'])->name('upload.proof');
Route::post('/PetOwner/uploadproof/{id}',[UploadController::class,'uploadproofpetowner'])->name('upload.proof.petowner');

Route::post('/AnimalShelter/waitingsubscription/{id}',[AnimalShelterManagement::class,'waitingsub'])->name('waiting.subscription');
Route::post('/PetOwner/waitingsubscription/{id}',[PetOwnerManagement::class,'waitingsub'])->name('waiting.subscription.petowner');

Route::post('/Admin/feedback/{sub_id}/{receiver_id}',[AdminController::class,'feedback']);

Route::post('/Admin/feedbackdonationmessage/{id}',[AnimalShelterManagement::class,'feedbackmessage'])->name('view.feedback.message');
Route::post('/Admin/feedbackdonationmessageerror/{id}',[AnimalShelterManagement::class,'feedbackmessageerror'])->name('view.feedback.message.error');
Route::post('/Admin/message/{id}',[AnimalShelterManagement::class,'message'])->name('message');


Route::get('/test',[UploadController::class,'view']);
Route::post('/SecondaryIntro',[AnimalShelterManagement::class,'secondaryIntro'])->name('second.intro');
Route::get('/User/logout',[LoginController::class,'logout']);
Route::post('/Cat',[AnimalShelterManagement::class,'CatLifeStage'])->name('catlife.stage');
Route::post('/Dog',[AnimalShelterManagement::class,'DogLifeStage'])->name('doglife.stage');
Route::post('/Both',[AnimalShelterManagement::class,'BothLifeStage'])->name('bothlife.stage');
Route::post('/CatFee',[AnimalShelterManagement::class,'CatFee'])->name('cat.fee');
Route::post('/DogFee',[AnimalShelterManagement::class,'DogFee'])->name('dog.fee');
Route::post('/BothFee',[AnimalShelterManagement::class,'BothFee'])->name('both.fee');
Route::post('/AddCatBreed',[AnimalShelterManagement::class,'addcatbreed'])->name('catbreed.add');
Route::post('/DeleteCatBreed',[AnimalShelterManagement::class,'deletecatbreed'])->name('catbreed.delete');
Route::post('/AddDogBreed',[AnimalShelterManagement::class,'adddogbreed'])->name('dogbreed.add');
Route::post('/DeleteDogBreed',[AnimalShelterManagement::class,'deletedogbreed'])->name('dogbreed.delete');
Route::post('/AddBothBreed',[AnimalShelterManagement::class,'addbothbreed'])->name('bothbreed.add');
Route::post('/DeleteBothBreed',[AnimalShelterManagement::class,'deletebothbreed'])->name('bothbreed.delete');
Route::post('/SaveBothBreed',[AnimalShelterManagement::class,'savebothbreed'])->name('bothbreed.save');
Route::post('/SaveCatBreed',[AnimalShelterManagement::class,'savecatbreed'])->name('catbreed.save');
Route::post('/SaveDogBreed',[AnimalShelterManagement::class,'savedogbreed'])->name('dogbreed.save');
Route::post('/Register/save',[RegisterController::class,'registerAnimalShelter'])->name('Register.save');
Route::post('/Register/upload',[UploadController::class,'store']);
Route::post('/test/upload',[UploadController::class,'upload'])->name('up.upload');
Route::get('/test/delete',[UploadController::class,'delete'])->name('dropzone.delete');
Route::post('/Register/delete',[UploadController::class,'delete']);
Route::post('/User/login',[LoginController::class,'check']);
Route::post('/Register/petOwner',[RegisterController::class,'registerPetOwner']);   
Route::post('/dashboard',[AnimalShelterManagement::class,'AddAnimal']);
Route::post('/AnimalManagement',[AnimalShelterManagement::class,'AddAnimal']);
Route::post('/AdoptionPolicy',[AnimalShelterManagement::class,'AddPolicy']);
Route::post('/AdoptionPolicy/edit/{id}',[AnimalShelterManagement::class,'EditPolicy']);
Route::post('/AdoptionPolicy/delete/{id}',[AnimalShelterManagement::class,'DeletePolicy']);
Route::post('/vaccine/vac',[AnimalShelterManagement::class,'AddVaccine']);
Route::post('/vaccine/editvac/{id}',[AnimalShelterManagement::class,'EditVaccine']);
Route::post('/vaccine/deletevac/{id}',[AnimalShelterManagement::class,'DeleteVaccine']);
Route::post('/deworm/editdeworm/{id}',[AnimalShelterManagement::class,'EditDeworm']);
Route::post('/deworm/deletedeworm/{id}',[AnimalShelterManagement::class,'DeleteDeworm']);
Route::post('/vaccine/deworm',[AnimalShelterManagement::class,'AddDeworm']);
Route::post('/AnimalManagement/{id}',[AnimalShelterManagement::class,'DeleteAnimal']);
Route::post('/EditProfile/{id}',[AnimalShelterManagement::class,'UpdateProfile']);
Route::post('/Profile/deactivate',[AnimalShelterManagement::class,'DeactivateProfileAccess'])->name('deactivate.shelter');
Route::post('/Profile/deactivation',[AnimalShelterManagement::class,'Deactivation'])->name('deactivation.account');
Route::post('/UpdatePassword/{id}',[AnimalShelterManagement::class,'UpdatePassword']);
Route::post('/AllocationVaccine/{id}/{vac_id}',[AnimalShelterManagement::class,'Allocation_Vaccine']);
Route::post('/AllocationDeworm/{id}/{dew_id}',[AnimalShelterManagement::class,'Allocation_Deworm']);
Route::post('/{id}',[AnimalShelterManagement::class,'UpdateAnimal']);
Route::get('/Register/petOwner',[RegisterController::class,'viewregisterPetOwner']);  
Route::get('/Register/signup',[RegisterController::class,'register']);
Route::get('/User/login',[LoginController::class,'login']); 
Route::get('/verify',[RegisterController::class,'verifyUser'])->name('verify.user');
Route::get('/verify/petowner',[RegisterController::class,'verifyPetUser'])->name('verifyPetUser.user');
Route::get('/auto/logout/{shelter_id}',[LoginController::class,'autologout']);

Route::group(['middleware'=>['Authcheck','DonationCheckNotif']],function(){
    Route::group(['middleware'=>['CheckPostCredits']],function(){
        Route::get('/petbook/allocate',[AnimalShelterManagement::class,'petbook_allocate'])->name('petbook.allocate');
        Route::get('/petbook/viewbook',[AnimalShelterManagement::class,'petbook_viewbook'])->name('petbook.view');
        Route::get('/petbook/fetch',[AnimalShelterManagement::class,'load_books'])->name('petbook.fetch');
        Route::get('/petbook/generate',[AnimalShelterManagement::class,'petbook_generate'])->name('petbook.generate');
        Route::get('/petbook/details/{id}',[AnimalShelterManagement::class,'petbook_details'])->name('petbook.details');
        Route::get('/petbook/refresh/{vac_id}/{dew_id}',[AnimalShelterManagement::class,'petbook_refresh'])->name('petbook.refresh');
    
        Route::get('/postpet/postdelete/{id}',[AnimalShelterManagement::class,'post_pet_delete'])->name('post.pet.delete');
        Route::post('/postpet/postupdate/{id}',[AnimalShelterManagement::class,'post_pet_update'])->name('post.pet.update');
        Route::post('/postpet/postsave/{id}',[AnimalShelterManagement::class,'post_pet_save'])->name('post.pet.save');
        Route::get('/postpet/load_post',[AnimalShelterManagement::class,'load_post'])->name('post.load');
        Route::get('/postpet/view',[AnimalShelterManagement::class,'postview'])->name('post.view');
        Route::get('/postpet/loadfee/{id}',[AnimalShelterManagement::class,'loadfee'])->name('load.fee');
        Route::get('/postpet/viewupdatepost/{id}',[AnimalShelterManagement::class,'postupdate'])->name('post.update');
        Route::get('/postpet/create/{id}',[AnimalShelterManagement::class,'postcreate'])->name('post.create');
        Route::post('/postpet/uploadphoto/{id}',[UploadController::class,'uploadphotopost'])->name('post.uploadphoto');
        Route::get('/postpet/fetchpostphoto/{id}',[UploadController::class,'fetchpostphotos'])->name('post.fetch');
        Route::get('/postpet/deletepostphoto',[UploadController::class,'deletepostphotos'])->name('postphoto.delete');

        Route::get('/vaccine',[AnimalShelterManagement::class,'vaccine_dewormView']);
        Route::get('/allocate',[AnimalShelterManagement::class,'allocate_view']);
        Route::get('/Profile/{shelter_id}',[AnimalShelterManagement::class,'ViewProfile']);
        Route::get('/test/fetchimage',[UploadController::class,'fetch'])->name('dropzone.fetch');
        Route::get('/AdoptionPolicy',[AnimalShelterManagement::class,'ViewPolicy']);
        Route::get('/AnimalManagement',[AnimalShelterManagement::class,'animal_view']);
        Route::get('/AllocateVaccine/{id}',[AnimalShelterManagement::class,'Allocate_Vaccine_Animal']);
        Route::get('/AllocationVaccine/{id}/{vac_id}',[AnimalShelterManagement::class,'ViewAllocationVaccine'])->name('vaccine.allocate');
        Route::get('/AllocationDeworm/{id}/{dew_id}',[AnimalShelterManagement::class,'ViewAllocationDeworm']);
        Route::get('/AllocateDeworm/{id}',[AnimalShelterManagement::class,'Allocate_Deworm_Animal']); 
        Route::get('/ViewEditAnimal/{id}',[AnimalShelterManagement::class,'ViewEditAnimal'])->name('ViewEditAnimal'); 
        Route::get('/Vaccinehistory',[AnimalShelterManagement::class,'ViewVaccineHistory']);
        Route::get('/Dewormhistory',[AnimalShelterManagement::class,'ViewDewormHistory']);
        Route::get('/Profile/Edit/{shelter_id}',[AnimalShelterManagement::class,'ViewEditProfile']);
        Route::get('/Dashboard/deact',[AnimalShelterManagement::class,'ViewDeactDash'])->name('deactpage');
        Route::get('/Dashboard/request/{shelter_id}',[AnimalShelterManagement::class,'RequestActivation'])->name('request.reactivation');
        Route::get('/AnimalShelter/viewdonation',[AnimalShelterManagement::class,'viewdonation'])->name('view.donation');
        Route::get('/AnimalShelter/Adoption',[AnimalShelterManagement::class,'adoptionrequests'])->name('adoption.request.shelter');

        Route::get('/customselection/get_fee',[DropDownController::class,'get_fee'])->name('get.fee');
        Route::get('/customselection/load_adoption',[DropDownController::class,'load_adoption'])->name('load.adoption');
        Route::get('/customselection/load_category',[DropDownController::class,'load_category'])->name('load.category');
        Route::get('/customselection/category',[DropDownController::class,'selection_category'])->name('selection.category');
        Route::get('/customselection/view',[DropDownController::class,'selection_view'])->name('selection.view');
        Route::get('/customselection/addcategory',[DropDownController::class,'addcategory'])->name('selection.add');
        Route::get('/customselection/selectionadoption',[DropDownController::class,'selection_adoption'])->name('selection.adoption');
        Route::get('/customselection/breed',[DropDownController::class,'selection_breed'])->name('selection.breed');
        Route::get('/customselection/load_breed',[DropDownController::class,'load_breed'])->name('load.breed');
        Route::post('/customselection/adddog_breed',[DropDownController::class,'adddog_breed'])->name('adddog.breed');
        Route::post('/customselection/deletedog_breed/{id}',[DropDownController::class,'deletedogbreed']);
        Route::post('/customselection/addcat_breed',[DropDownController::class,'addcat_breed'])->name('addcat.breed');
        Route::post('/customselection/deletecat_breed/{id}',[DropDownController::class,'deletecatbreed']);
        Route::get('/customselection/addadoptionfee',[DropDownController::class,'addadoptionfee'])->name('selection.addadoptionfee');
        Route::post('/customselection/selection_adoption_savefee',[DropDownController::class,'selection_adoption_savefee'])->name('selection.adoption.savefee');
    });
    Route::get('/dashboard',[AnimalShelterManagement::class,'Animalshelter_dashboard'])->name('dash');
    Route::get('/tempdashboard',[AnimalShelterManagement::class,'Animalshelter_tempdashboard']);
    Route::get('/AnimalShelter/subscription/{id}',[AnimalShelterManagement::class,'choosesubscription'])->name('choose.subscription');
    Route::get('/AnimalShelter/viewwaitsubscription/{id}',[AnimalShelterManagement::class,'viewwaitsubscription'])->name('view.wait.subscription');
    Route::get('/AnimalShelter/cancelsub/{id}',[AnimalShelterManagement::class,'cancelsub'])->name('cancel.subscription');
});

Route::group(['middleware'=>['AdminCheck']],function(){ 
    Route::view('/','index');
});

Route::group(['middleware'=>['PetOwnerCheck']],function(){ 
    Route::group(['middleware'=>['CheckPostCreditsPetOwner']],function(){
        Route::get('/Profile/petowner/{petowner_id}',[PetOwnerManagement::class,'ViewProfile']);
        Route::get('/Profile/Edit/petowner/{petowner_id}',[PetOwnerManagement::class,'ViewEditProfile']);
        Route::get('/test/fetchimage/petowner',[UploadController::class,'fetchpetownerphotos'])->name('dropzone.fetch.petowner');
        Route::get('/test/delete/petowner',[UploadController::class,'deletepetownerphoto'])->name('dropzone.delete.petowner');
        Route::get('/AnimalManagement/petowner',[PetOwnerManagement::class,'animal_view'])->name('pet.view');
        Route::get('/ViewEditAnimal/petowner/{id}',[PetOwnerManagement::class,'ViewEditAnimal'])->name('edit.pet'); 
        Route::get('/AdoptionPolicy/petowner',[PetOwnerManagement::class,'ViewPolicy'])->name('view.policy');
        Route::get('/petbook/petowner/viewbook',[PetOwnerManagement::class,'petbook_viewbook'])->name('petbook.view.petowner');
        Route::get('/petbook/petowner/fetch',[PetOwnerManagement::class,'load_books'])->name('petbook.fetch.petowner');
        Route::get('/petbook/petowner/details/{id}',[PetOwnerManagement::class,'petbook_details'])->name('petbook.details.petowner');
        Route::get('/petbook/petowner/generate',[PetOwnerManagement::class,'petbook_generate'])->name('petbook.generate.petowner');
        Route::get('/petbook/petowner/details/{id}',[PetOwnerManagement::class,'petbook_details'])->name('petbook.details.petowner');
        Route::get('/vaccine/petowner',[PetOwnerManagement::class,'vaccine_dewormView'])->name('vaccine.deworm.view');
        Route::get('/allocate/petowner',[PetOwnerManagement::class,'allocate_view'])->name('allocate.view');
        Route::get('/AllocateVaccine/petowner/{id}',[PetOwnerManagement::class,'Allocate_Vaccine_Animal'])->name('allocatevaccinedeworm.petowner');
        Route::get('/AllocationVaccine/view/petowner/{id}/{vac_id}',[PetOwnerManagement::class,'ViewAllocationVaccine'])->name('vaccine.allocate');
        Route::get('/AllocationDeworm/petowner/{id}/{dew_id}',[PetOwnerManagement::class,'ViewAllocationDeworm']);
        Route::get('/postpet/view/petowner',[PetOwnerManagement::class,'postview'])->name('post.view.petowner');
        Route::get('/postpet/load_post/petowner',[PetOwnerManagement::class,'load_post'])->name('post.load.petowner');
        Route::get('/postpet/create/petowner/{id}',[PetOwnerManagement::class,'postcreate'])->name('post.create.petowner');
        Route::get('/postpet/fetchpostphoto/petowner/{id}',[UploadController::class,'fetchpostphotos_petowner'])->name('post.fetch.petowner');
        Route::get('/postpet/deletepostphoto/petowner',[UploadController::class,'deletepostphotos_petowner'])->name('postphoto.delete.petowner');
        Route::get('/postpet/loadfee/petowner/{id}',[PetOwnerManagement::class,'loadfee'])->name('load.fee.petowner');
        Route::get('/postpet/viewupdatepost/petowner/{id}',[PetOwnerManagement::class,'postupdate'])->name('post.update.petowner');
        Route::get('/postpet/postdelete/petowner/{id}',[PetOwnerManagement::class,'post_pet_delete'])->name('post.pet.delete.petowner');
        Route::get('/customselection/petowner/view',[DropDownController::class,'selection_view_petowner'])->name('selection.view.petowner');
        Route::get('/customselection/petowner/category',[DropDownController::class,'selection_category_petowner'])->name('selection.category.petowner');
        Route::get('/customselection/petowner/load_category',[DropDownController::class,'load_category_petowner'])->name('load.category.petowner');
        Route::get('/customselection/petowner/addcategory',[DropDownController::class,'addcategory_petowner'])->name('selection.add.petowner');
        Route::get('/customselection/petowner/breed',[DropDownController::class,'selection_breed_petowner'])->name('selection.breed.petowner');
        Route::get('/customselection/petowner/selectionadoption',[DropDownController::class,'selection_adoption_petowner'])->name('selection.adoption.petowner');
        Route::get('/customselection/petowner/load_adoption',[DropDownController::class,'load_adoption_petowner'])->name('load.adoption.petowner');
        Route::get('/customselection/get_fee/petowner',[DropDownController::class,'get_fee_petowner'])->name('get.fee.petowner');
        
        Route::get('/PetOwner/Adoption',[PetOwnerManagement::class,'adoptionrequests'])->name('adoption.request.petowner');

    });
    Route::get('/auto/logout/petowner/{petowner_id}',[LoginController::class,'autologoutpetowner']);
    Route::get('/tempdashboard/petowner',[PetOwnerManagement::class,'PetOwner_tempdashboard']);
    Route::get('/pet-owner/dashboard',[PetOwnerManagement::class,'dashboard']);
   
    Route::get('/Dashboard/deact/petowner',[PetOwnerManagement::class,'ViewDeactDash'])->name('deactpage.petowner');
    Route::get('/Dashboard/request/petowner/{petowner_id}',[PetOwnerManagement::class,'RequestActivation'])->name('request.reactivation.petowner');

    Route::get('/Petowner/viewwaitsubscription/{id}',[PetOwnerManagement::class,'viewwaitsubscription'])->name('owner.view.wait.subscription');
    Route::get('/PetOwner/subscription/{id}',[PetOwnerManagement::class,'choosesubscription'])->name('choose.subscription.petowner');
    Route::get('/PetOwner/cancelsub/{id}',[PetOwnerManagement::class,'cancelsub'])->name('cancel.subscription.petowner');

});

Route::get('/dashboard/gettype/petowner',[DropDownController::class,'gettype_petowner'])->name('get.type.petowner');
Route::get('/dashboard/getbreed/petowner',[DropDownController::class,'getbreed_petowner'])->name('get.breed.petowner');
Route::get('/dashboard/getbreed',[DropDownController::class,'getbreed'])->name('get.breed');
Route::get('/dashboard/gettype',[DropDownController::class,'gettype'])->name('get.type');
Route::get('/allocate/vaccineselection',[DropDownController::class,'vaccinefetch'])->name('vaccine.fetch');
Route::get('/allocate/dewormselection',[DropDownController::class,'dewormfetch'])->name('deworm.fetch');

Route::group(['middleware'=>['AdopterNotifRequest']],function(){ 
    Route::get('/Reactivation/petowner/{petowner_id}',[AdminController::class,'reactivationpetowner'])->name('approve.reactivation.petowner');
    Route::get('/Admin/dashboard',[AdminController::class,'dashboard'])->name('admindash');
    Route::get('/Admin/viewpetowner',[AdminController::class,'ViewPetOwners'])->name('viewpetowner');
    Route::get('/Admin/viewshelter',[AdminController::class,'ViewAnimalShelters'])->name('viewshelter');
    Route::get('/Admin/viewpetownerdetails/{petowner_id}',[AdminController::class,'ViewPetOwnerDetails'])->name('viewownerdetails');
    Route::get('/Admin/viewshelterdetails/{shelter_id}',[AdminController::class,'ViewAnimalSheltersDetails'])->name('viewdetails');
    Route::get('/Admin/viewpetownerenlargedetails/{filename}/{petowner_id}',[AdminController::class,'ViewPetOwnerEnlargeDetails'])->name('viewenlargepetowner');
    Route::get('/Admin/viewshelterenlargedetails/{filename}/{shelter_id}',[AdminController::class,'ViewAnimalSheltersEnlargeDetails'])->name('viewenlarge');
    Route::get('/Admin/viewshelter/{shelter_id}',[AdminController::class,'ApproveShelterApp'])->name('approve.shelter');
    Route::get('/Admin/viewpetowner/{petowner_id}',[AdminController::class,'ApprovePetOwnerApp'])->name('approve.petowner');
    Route::get('/Admin/ApproveReactivationAdopter/{id}',[AdminController::class,'approveadopterrequest'])->name('approve.adopter.request');
    Route::get('/Admin/viewshelterReject/{shelter_id}',[AdminController::class,'RejectShelterApp'])->name('reject.shelter');
    Route::get('/Admin/viewpetownerReject/{petowner_id}',[AdminController::class,'RejectPetOwnerApp'])->name('reject.petowner');
    Route::get('/Reject/{shelter_id}',[AdminController::class,'reject_remove_shelter'])->name('remove.shelter'); 
    Route::get('/Reject/petowner/{petowner_id}',[AdminController::class,'reject_remove_petowner'])->name('remove.petowner'); 
    Route::get('/Reactivation/{shelter_id}',[AdminController::class,'reactivation'])->name('approve.reactivation');
    Route::get('/Admin/viewreactivation',[AdminController::class,'viewReactivation'])->name('view.reactivation');
    Route::get('/Admin/viewreactivation/petowner',[AdminController::class,'viewReactivationpetowner'])->name('view.reactivation.petowner');
    Route::get('/Admin/approveAllreactivation',[AdminController::class,'approveAll'])->name('approve.all');
    Route::get('/Admin/viewsubscription',[AdminController::class,'ViewSubscription'])->name('view.subscription');
    Route::get('/Admin/viewaddsubscription',[AdminController::class,'Viewaddsubscription'])->name('view.add.subscription');
    Route::get('/Admin/vieweditsubscription/{id}',[AdminController::class,'Vieweditsubscription'])->name('view.edit.subscription');
    Route::get('/Admin/loadsubscription/{id}',[AdminController::class,'loadsubscription'])->name('load.subscription');
    Route::get('/Admin/loadsubscription/remove/{id}',[AdminController::class,'loadsubscriptionremove'])->name('load.subscription.remove');
    Route::get('/Admin/loadproof/{id}/{sub_id}',[UploadController::class,'loadproof'])->name('load.proof');
    Route::get('/PetOwner/loadproof/{id}/{sub_id}',[UploadController::class,'loadproofpetowner'])->name('load.proof.petowner');
    Route::get('/Admin/deleteproof',[UploadController::class,'deleteproof'])->name('delete.proof');
    Route::get('/PetOwner/deleteproof',[UploadController::class,'deleteproofpetowner'])->name('delete.proof.petowner');
    Route::get('/Admin/viewproofpayment',[AdminController::class,'viewproofpayment'])->name('view.proof.payment');
    Route::get('/Admin/viewenlargeproof/{sub_id}/{user_id}',[AdminController::class,'viewenlargeproofpayment'])->name('view.proof.enlarge');
    Route::get('/Admin/Approveproofpayment/{sub_id}/{user_id}',[AdminController::class,'approveproofpayment'])->name('approve.proof.payment');
    Route::get('/Admin/Rejectproofpayment/{sub_id}/{user_id}',[AdminController::class,'rejectproofpayment'])->name('reject.proof.payment');
    Route::get('/Admin/ViewReactivationAdopter',[AdminController::class,'ViewAdopterRequest'])->name('view.reactivation.adopter');
    
});

//markAllAsRead
Route::get('markAllAsReadNotif/{email}', function($email){
    $admin = Admin::where('email',$email)->first();
    $shelter = AnimalShelter::where('email',$email)->first();
    $petowner = PetOwner::where('email',$email)->first();
           
    if($admin){
        $admin->unreadNotifications->markAsRead();
        return redirect()->back();
    } 
    else if($shelter){
        $shelter->unreadNotifications->markAsRead();
        return redirect()->back();
    }
    elseif($petowner){
        $petowner->unreadNotifications->markAsRead();
        return redirect()->back();
    }

    
})->name('markread');

Route::get('autologoutReactivation/{user_id}', function($user_id){
    if(session('LoggedUser') == $user_id){
        session()->pull('LoggedUser');
        return redirect('/User/login')->with('stat','Your account has been reactivated!');
    } 
    if(session('LoggedUserPet') == $user_id){
        session()->pull('LoggedUserPet');
        return redirect('/User/login')->with('stat','Your account has been reactivated!');
    } 

})->name('auto.reactivation');

//markasRead
Route::get('markAsReadNotif/{email}', function($email){
    $admin = Admin::where('email',$email)->first();
    $shelter = AnimalShelter::where('email',$email)->first();
    $petowner = PetOwner::where('email',$email)->first();

    if($admin){
        $admin->unreadnotifications->first()->markAsRead();
        return redirect()->back();
    }
    else if($shelter){
        $shelter->unreadnotifications->first()->markAsRead();
        return redirect()->back();
    }
    elseif($petowner){
        $petowner->unreadnotifications->first()->markAsRead();
        return redirect()->back();
    }
})->name('marksingleread');
//clear
Route::get('deletenotif/{email}', function($email){   
    $admin = Admin::where('email',$email)->first();
    $shelter = AnimalShelter::where('email',$email)->first();
    $petowner = PetOwner::where('email',$email)->first();

    if($shelter){
        if($shelter->unreadnotifications()->count()){
            return redirect()->back()->with('status1','You must mark all notifications as read');
        }
        else{
            $shelter->readnotifications()->delete();
            return redirect()->back()->with('status','You cleared all your notifications');
        } 
    }
    else if($admin){
        if($admin->unreadnotifications()->count()){
            return redirect()->back()->with('status1','You must mark all notifications as read');
        }
        else{
            $admin->readnotifications()->delete();
            return redirect()->back()->with('status','You cleared all your notifications');
        } 
    }
    elseif ($petowner) {
        if($petowner->unreadnotifications()->count()){
            return redirect()->back()->with('status1','You must mark all notifications as read');
        }
        else{
            $petowner->readnotifications()->delete();
            return redirect()->back()->with('status','You cleared all your notifications');
        } 
    }
})->name('delete');
