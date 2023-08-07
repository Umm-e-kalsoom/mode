<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserApp;
use Illuminate\Http\Request;
use DB;
class UserPhotoController extends Controller
{


  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

  public function updateUserPhoto(Request $request)
  {


        $user_cat =  $request->get('user_cat');
        $image= $request->file('image');
        $date_heure = date('Y-m-d H:i:s');
        $photo = '';
        if($user_cat == "user_app"){
        $id_user = $request->get('id_user');
        if(empty($image))
        {
            $response['success']= 'Failed';
            $response['error']= 'Image Not Found';
        } else
        {
        $image = '';
        $file = $request->file('image');
        $extenstion = $file->getClientOriginalExtension();
        $time = time().'.'.$extenstion;
        $filename = 'User_photo'.$time;
        $file->move(public_path('assets/images/users'), $filename);

        $updatedata = DB::update('update tj_user_app set photo = ?,photo_path = ?,modifier = ? where id = ?',[$image,$filename,$date_heure,$id_user]);

        $sql = DB::table('tj_user_app')
        ->select('*')
        ->where('id','=',DB::raw($id_user))
        ->orderby('modifier','Desc')
        ->get();


        foreach($sql as $row)
        if($row){
            if(!empty($image))
            $image = $row->photo_path;
            $image_nic = $row->photo_nic_path;
            if($row->photo_path != ''){
                if($row->photo_path )
                {
                    $image = asset('assets/images/users').'/'. $row->photo_path;
                }
                else
                {
                    $image = asset('assets/images/placeholder_image.jpg');

                }
                $row->photo_path = $image;
            }
            $row->photo =  $photo;
            $row->photo_nic =  $photo;
            if($image_nic != ''){
                if($image_nic )
                {
                    $image_user = asset('assets/images/users').'/'. $image_nic;
                }
                else
                {
                    $image_user = asset('assets/images/placeholder_image.jpg');

                }
                $row->photo_nic_path = $image_user;
            }
            $response['success']= 'Success';
            $response['error']= null;
            $response['message']= 'Image Updated';
            $response['data'] = $row;
        } else {
            $response['success']= 'Failed';
            $response['error']= 'Image Not Updated';
        }
    }
            }elseif($user_cat == "driver"){
            $id_user = $request->get('id_user');
            if(empty($image))
            {
                $response['success']= 'Failed';
                $response['error']= 'Image Not Found';
            } else
            {
            $image = '';
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $time = time().'.'.$extenstion;
            $filename = 'Driver_photo'.$time;
            $file->move('assets/images/driver', $filename);

            $updatedata = DB::update('update tj_conducteur set photo = ?,photo_path = ?,modifier = ? where id = ?',[$image,$filename,$date_heure,$id_user]);

            $sql = DB::table('tj_conducteur')
            ->select('*')
            ->where('id','=',DB::raw($id_user))
            ->orderby('modifier','Desc')
            ->get();


            foreach($sql as $row)
            if($row){
                if(!empty($image))
            // $image = $row->photo_path;
            $image_nic = $row->photo_nic_path;
            $row->photo =  $photo;
            $row->photo_nic =  $photo;
            $row->photo_car_service_book =  $photo;
            $row->photo_licence =  $photo;
            $row->photo_road_worthy =  $photo;
            if( $row->photo_path != ''){
                if( $row->photo_path)
                {
                    $image = asset('assets/images/driver').'/'. $row->photo_path;
                }
                else
                {
                    $image = asset('assets/images/placeholder_image.jpg');

                }
                $row->photo_path = $image;
            }
            if($row->photo_licence_path != ''){
                if($row->photo_licence_path )
                    $image_lic = asset('assets/images/driver').'/'. $row->photo_licence_path;
                }
                else
                {
                    $image_lic = asset('assets/images/placeholder_image.jpg');

                }
                $row->photo_licence_path = $image_lic;
            }
            if($row->photo_nic_path != ''){
                if($row->photo_nic_path )
                {
                    $image_user = asset('assets/images/driver').'/'. $row->photo_nic_path;
                }
                else
                {
                    $image_user = asset('assets/images/placeholder_image.jpg');

                }
                $row->photo_nic_path = $image_user;
            }
            if($row->photo_car_service_book_path != ''){
                if($row->photo_car_service_book_path )
                {
                    $image_car = asset('assets/images/driver').'/'. $row->photo_car_service_book_path;
                }
                else
                {
                    $image_car = asset('assets/images/placeholder_image.jpg');

                }
                $row->photo_car_service_book_path = $image_car;
            }
            if($row->photo_road_worthy_path != ''){
                if($row->photo_road_worthy_path )s
                {
                    $image_road = asset('assets/images/driver').'/'. $row->photo_road_worthy_path;
                }
                else
                {
                    $image_road = asset('assets/images/placeholder_image.jpg');

                }
                $row->photo_road_worthy_path = $image_road;
            }
                $response['success']= 'Success';
                $response['error']= null;
                $response['message']= 'Image Updated';
                $response['data'] = $row;
            } else {
                $response['success']= 'Failed';
                $response['error']= 'Image Not Updated';
            }
        }
    }else{
        $response['success']= 'Failed';
        $response['error']= 'Not Found';
    }
    return response()->json($response);
  }

}
