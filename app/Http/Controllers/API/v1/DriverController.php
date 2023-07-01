<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use DB;

class DriverController extends Controller
{

    public function __construct()
    {
        $this->limit = 20;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = Driver::all();
        $users = Driver::paginate($this->limit);
        return response()->json($users);
    }

    public function getData(Request $request)
    {

        $lat1 = $request->get('lat1');
        $lng1 = $request->get('lng1');
        $lat2 = $request->get('lat2');
        $lng2 = $request->get('lng2');
        $sql = DB::table('tj_type_vehicule')
            ->crossJoin('tj_vehicule')
            ->crossJoin('tj_conducteur')
            ->select('tj_conducteur.id', 'tj_conducteur.nom', 'tj_type_vehicule.libelle', 'tj_type_vehicule.status', 'tj_type_vehicule.currency',
                'tj_type_vehicule.prix', 'tj_conducteur.prenom', 'tj_conducteur.phone', 'tj_conducteur.email',
                'tj_conducteur.online', 'tj_conducteur.photo_path as photo', 'tj_conducteur.latitude', 'tj_conducteur.longitude',
                'tj_vehicule.id as idVehicule', 'tj_vehicule.brand', 'tj_vehicule.model', 'tj_vehicule.color', 'tj_vehicule.numberplate',
                'tj_vehicule.passenger', 'tj_type_vehicule.libelle as typeVehicule')
            ->where('tj_vehicule.id_type_vehicule', '=', DB::raw('tj_type_vehicule.id'))
            ->where('tj_vehicule.id_conducteur', '=', DB::raw('tj_conducteur.id'))
            ->where('tj_vehicule.statut', '=', 'yes')->where('tj_conducteur.statut', '=', 'yes')
            ->where('tj_conducteur.is_verified', '=', '1')->where('tj_conducteur.online', '!=', 'no')
            ->where('tj_type_vehicule.status', '=', 'yes')
            ->where('tj_conducteur.latitude', '!=', '')->where('tj_conducteur.longitude', '!=', '')->get();
//		}
        $allDistance = array();
        if ($sql->count() > 0) {
            $output = array();
            foreach ($sql as $row) {

                $id_conducteur = $row->id;
                if ($row->latitude != '' && $row->longitude != '')
                    $row->distance = DriverController::distance($row->latitude, $row->longitude, $lat1, $lng1);
                $row->destinationDistance = DriverController::distance($lat1, $lng1, $lat2, $lng2);
                $row->unit = 'KM';
                $allDistance[] = $row->distance;
                $sql_nb_avis = DB::table('tj_note')->select(DB::raw("COUNT(id) as nb_avis"), DB::raw("SUM(niveau) as somme"))->where('id_conducteur', '=', DB::raw($row->idVehicule))->get();
                $row->price = round($row->destinationDistance * floatval($row->prix), 0);
                if (!empty($sql_nb_avis)) {

                    foreach ($sql_nb_avis as $row_nb_avis) {

                        $somme = $row_nb_avis->somme;
                        $nb_avis = $row_nb_avis->nb_avis;

                        if ($nb_avis != 0) {
                            $moyenne = $somme / $nb_avis;
                        } else {
                            $moyenne = 0;
                        }
                    }
                } else {
                    $somme = 0;
                    $nb_avis = 0;
                    $moyenne = 0;
                }

                $row->moyenne = $moyenne;

                $sql_total = DB::table('tj_requete')->select(DB::raw("COUNT(id) as total_completed_ride"))->where('id_conducteur', '=', DB::raw($id_conducteur))->where('statut', '=', 'completed')->get();

                foreach ($sql_total as $row_total) {
                    $row->total_completed_ride = $row_total->total_completed_ride;
                }

                if ($row->photo != '') {

                    if (file_exists('assets/images/driver' . '/' . $row->photo)) {
                        $image_user = asset('my-assets/images/driver') . '/' . $row->photo;
                    } else {
                        $image_user = asset('assets/images/placeholder_image.jpg');

                    }
                    $row->photo = $image_user;
                }
                if ($row->distance <= 20) {
                    $output[] = $row;
                }

            }

            function cmp($a, $b)
            {
                if ($a->distance == $b->distance)
                    return 0;
                return ($a->distance < $b->distance) ? -1 : 1;
            }

            if (!is_null($output)) {
                usort($output, 'App\Http\Controllers\API\v1\cmp');
            }
            if (count($output) > 0) {
                $response['success'] = 'Success';
                $response['error'] = null;
                $response['message'] = 'Successfully fetched data';
                $response['data'] = $output;
                return response()->json($response);
            }else {
                $response['success'] = 'Success';
                $response['error'] = null;
                $response['message'] = 'No driver found';
                $response['data'] = $output;
                return response()->json($response);
            }
        } else {
            $response['success'] = 'Success';
            $response['error'] = null;
            $response['message'] = 'No vehicle currently available in your area';
            $response['data'] = null;
            return response()->json($response, 422);
        }

    }

    // public static function cmp($a,$b){
    //   if ($a["distance"]==$b["distance"])
    //       return 0;
    //       return ($a["distance"] < $b["distance"])?-1:1;
    // }

    public static function distance($lat1, $lng1, $lat2, $lng2)
    {
        $rad = M_PI / 180;
        return acos(sin($lat2 * $rad) * sin($lat1 * $rad) + cos($lat2 * $rad) * cos($lat1 * $rad)
                * cos($lng2 * $rad - $lng1 * $rad)) * 6371;// Kilometers
    }

    public function changeStatus(Request $request)
    {
        $table = DB::table('tj_conducteur')->where('id', '=', $request->get('tj_conducteur'))->first();
        if ($table->online == 'yes') {
            DB::table('tj_conducteur')->where('id', '=', $request->get('tj_conducteur'))->update(['online' => 'no']);
            $message = 'Your status is active now';
        } else if ($table->online == 'no') {
            DB::table('tj_conducteur')->where('id', '=', $request->get('tj_conducteur'))->update(['online' => 'yes']);
            $message = 'Your status is inactive';
        }
        return response()->json(['status' => 200, 'message' => $message, 'data' => $table = DB::table('tj_conducteur')->where('id', '=', $request->get('tj_conducteur'))->first()]);
    }
}
