<?php
namespace App\Helper;
use App\Location;
use DB;
class General
{
    
   
    /**
    * opening and Close days helper
    *
    * 
    */

    public static function openingdays($days) {

        if($days->open_Monday != ""){
            $monday ='Open: '.$days->open_Monday.' - Close: '.$days->closed_Monday;
        }
        else{
            $monday ="Not Opened";
        }
        if($days->open_Tuesday != ""){
            $tuesday='Open: '.$days->open_Tuesday.' - Close: '.$days->closed_Tuesday;
        }
        else{
            $tuesday="Not Opened";
        }
        if($days->open_Wednesday != ""){
            $wednesday='Open: '.$days->open_Wednesday.' - Close: '.$days->closed_Wednesday;
        }
        else{
            $wednesday="Not Opened";
        }
        if($days->open_Thursday != ""){
            $thursday='Open: '.$days->open_Thursday.' - Close:'.$days->closed_Thursday;
        }
        else{
            $thursday="Not Opened";
        }
        if($days->open_Friday != ""){
            $friday='Open: '.$days->open_Friday.' - Close:'.$days->closed_Friday;
        }
        else{
            $friday="Not Opened";
        }
        if($days->open_Saturday != ""){
            $saturday='Open: '.$days->open_Saturday.' - Close: '.$days->closed_Saturday;
        }
        else{
            $saturday="Not Opened";
        }

        if($days->open_Sunday != ""){
            $sunday='Open: '.$days->open_Sunday.' - Close: '.$days->closed_Sunday;
        }
        else{
            $sunday="Not Opened";
        }

        return $data= array("monday"=>$monday,"tuesday"=>$tuesday,"wednesday"=>$wednesday,"thursday"=>$thursday,"friday"=>$friday,"saturday"=>$saturday,"sunday"=>$sunday);
    }


    public static function nearest_distance($nearest){
        

        $nearestarray= $nearest->toArray();
       
            $min = array_reduce($nearestarray, function($min, $details) {
                return min($min, $details['distance']);
            }, PHP_INT_MAX);

      
        return $min;

    }

    public static function getOpeningTimes( $opening_times){

    
        if($opening_times["monday"] !=""){

            $monday =["open_Monday"=>$opening_times["monday"]];

        }
        else{

            $monday ='';
        }  
        if(!empty($opening_times["tuesday"])){

            $tuesday =["open_Tuesday"=>$opening_times["tuesday"]];
        }
        else{

            $monday ='';
        }
        if(!empty($opening_times["wednesday"])){

            $wednesday =["open_Wednesday"=>$opening_times["wednesday"]];
        }
        else{

            $wednesday  ='';
        }
        if(!empty($opening_times["thursday"])){

            $thursday =["open_Thursday"=>$opening_times["thursday"]];
        }
        else{

            $thursday  ='';
        }
        if(!empty($opening_times["friday"])){

            $friday =["open_Friday"=>$opening_times["friday"]];
        }
        else{

            $friday ='';
        }
        if(!empty($opening_times["saturday"])){

            $saturday =["open_Saturday"=>$opening_times["saturday"]];
        }
        else{

            $saturday ='';
        }
        if(!empty($opening_times["sunday"])){

            $sunday =["open_Sunday"=>$opening_times["sunday"]];

        }
        else{

            $sunday ='';
        }

         $data= array($monday,$tuesday,$wednesday,$thursday,$friday,$saturday,$sunday);
        return  array_filter($data);
         
            
    }

    public static function getCloseTimes( $closing_times ){

        if($closing_times["monday"] !=""){

            $monday =["closed_Monday"=>$closing_times["monday"]];

        }
        else{

            $monday ='';
        }  
        if(!empty($closing_times["tuesday"])){

            $tuesday =["closed_Tuesday"=>$closing_times["tuesday"]];
        }
        else{

            $monday ='';
        }
        if(!empty($closing_times["wednesday"])){

            $wednesday =["closed_Wednesday"=>$closing_times["wednesday"]];
        }
        else{

            $wednesday  ='';
        }
        if(!empty($closing_times["thursday"])){

            $thursday =["closed_Thursday"=>$closing_times["thursday"]];
        }
        else{

            $thursday  ='';
        }
        if(!empty($closing_times["friday"])){

            $friday =["closed_Friday"=>$closing_times["friday"]];
        }
        else{
            
            $friday =''; 
        }
        if(!empty($closing_times["saturday"])){

            $saturday =["closed_Saturday"=>$closing_times["saturday"]];
        }
        else{

            $saturday ='';
        }
        if(!empty($closing_times["sunday"])){

            $sunday =["closed_Sunday"=>$closing_times["sunday"]];

        }
        else{

            $sunday ='';
        }

         $data= array($monday,$tuesday,$wednesday,$thursday,$friday,$saturday,$sunday);
        return  array_filter($data);
         
            
    }

   

    

}