<?php
namespace App\Http\Controllers\Api;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Location;
use App\Helper\PostCodeIO;
use App\Helper\General;
use Validator;

class LocationController extends BaseController
{
    /**
    * Display all list of the locations.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $locations = Location::all();
        //if locations data is empty return error message
        if (is_null($locations)) {
            return $this->sendError('Location not found.');
        }
        else{
            foreach($locations as $loc){
              
                //get postcode data
                $result= PostCodeIO::get($loc->postcode);

                //return address and opening times  
                $address=$result["admin_ward"].','.$result["admin_district"].','.$loc->postcode.','.$result["admin_county"];
                
                //get opening times for each location
                $days=General::openingdays($loc);
             
                //return address with opening hours 
                $data[]=array("postcode"=>$address,"monday"=>$days["monday"],"tuesday"=>$days["tuesday"],"wednesday"=>$days["wednesday"],"thursday"=>$days["thursday"],"friday"=>$days["friday"],"saturday"=>$days["saturday"],"sunday"=>$days["sunday"]);

            }
            return $this->sendResponse($data, 'Location retrieved successfully.');
        }
    }

    /**
    * Display the specified resource.
    *
    * @param Request $request
    * @return \Illuminate\Http\Response
    */

    public function GetNearestLocation(Request $request)
    {
        //get postcode request
        $postcode= $request->postcode;
        

        //validate postcode before comparison
        $result= PostCodeIO::validate($postcode);

        //if true, do comparison and return result
        if($result == true){
            
            $data= [];
            $result= PostCodeIO::get($postcode);
            $latitude=$result["longitude"];
            $longitude=$result["latitude"];
            $radius = 5000;
                
            //haversine formula for calculating distances from latitude and longitude  
            $getnearest = Location::select('*')
            ->selectRaw('( 3959 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?)) + sin( radians(?) ) * sin( radians( latitude ) ) )) AS distance', [$latitude, $longitude,$latitude])
            ->whereNotNull( 'latitude' )
            ->whereNotNull( 'longitude' )
            ->orderBy('distance', 'asc')
            ->havingRaw('distance <= ?', [$radius])
            ->get();
                    
            //return not found if request is null
            if(is_null($getnearest)) {
                return $this->sendError('Location not found.');
            }
            else{
               
                foreach($getnearest as $find){
                    //get postcode data
                    $postresult=PostCodeIO::get($find->postcode);

                    $address= $postresult["admin_district"].', '.$find->postcode.', '. $postresult["region"].', '.$postresult["primary_care_trust"];
                                
                    $days=General::openingdays($find);

                    array_push($data,["postcode"=>$address,"monday"=>$days["monday"],"tuesday"=>$days["tuesday"],"wednesday"=>$days["wednesday"],"thursday"=>$days["thursday"],"friday"=>$days["friday"],"saturday"=>$days["saturday"],"sunday"=>$days["sunday"]]);
                
                }
                //return formatted data
                return $this->sendResponse($data, 'Location retrieved successfully.');
            }
            //end 

        }
        else{
            // return invalid if validation is false
            return $this->sendError('Postcode is invalid');
        }
    }


    /**
    * Store a newly created location in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */

    public function CreateNewLocation(Request $request)
    {
        // if no  opening times in the request return status code and send error message.
        if ( empty( $request->opening_times ) || empty( $request->closing_times ) ) {
            return $this->sendError('Please provide opening and closing hours');
        }

        // Get postcode from the request.
        $postcode = $request->postcode;

        // Validate the postcode.
        $valLatLong= PostCodeIO::validate( $postcode );

        if ($valLatLong == false ) {

            return $this->sendError('Postcode is invalid, try again');
        }

        // get postcode data and opening times.
        $LatLong= PostCodeIO::get( $postcode );
        $returnopeningTimes = General::getOpeningTimes( $request->opening_times);
        $returnclosedTimes = General::getCloseTimes( $request->closing_times);
       
           
        $store =array(
            'postcode' => $postcode,
            'latitude' => $LatLong['latitude'],
            'longitude' => $LatLong['longitude'],
           
        );

        //store new location
        $location = Location::create($store);

        //update opening times
        foreach($returnopeningTimes as $opentimes){
            $location .= DB::table('locations')
            ->where('postcode', $postcode)
            ->update($opentimes);
        }

        //update closing times
        foreach($returnclosedTimes as $closedtimes){
            $location .= DB::table('locations')
            ->where('postcode', $postcode)
            ->update($closedtimes);
        }
        
        
        return $this->sendResponse($location, 'Location added successfully.' );
    }

}