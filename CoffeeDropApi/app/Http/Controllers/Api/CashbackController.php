<?php


namespace App\Http\Controllers\Api;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Cashback;


class CashbackController extends BaseController
{

    /**
    * Array of available coffee pods
    * @var array
    */

    protected $pods = [ 'Ristretto', 'Espresso', 'Lungo' ];

    /**
    * Array of coffee pods rewards
    * @var array
    */

    protected $cashbacks = ['Ristretto' => [ 2, 3, 5 ],'Espresso' => [ 4, 6, 10 ],'Lungo' => [ 6, 9, 15 ]];

    /**
    * calculate and return  cashback to customer
    *
    * @param Request $request
    * @return float
    */
    public function CalculateCashback( Request $request ) 
    {
        $allcashback = 0;
        // check all available coffee pods
        foreach ( $this->pods as $pod ) {
            // calculate  when coffee is available and return amount as integer
            if ( isset( $request->$pod ) && is_int( $request->$pod ) ) {
                $amount = $request->$pod;
                // get the amount and cashback of a given pod
                if ( $amount > 0 && $amount <= 50 ) {
                    $allcashback += $amount * $this->cashbacks[ $pod ][ 0 ];
                } elseif ( $amount > 50 && $amount <= 500 ) {
                    $allcashback += $amount * $this->cashbacks[ $pod ][ 1 ];
                } elseif ( $amount > 500 ) {
                    $allcashback += $amount * $this->cashbacks[ $pod ][ 2 ];
                }
            }
        }

        /*store request in cashback_request table*/
        $this->storeRequest($request, $allcashback);

        /*format value to  pounds*/
        return money_format( '%.2n', $allcashback / 100 );
    }

    /**
    * Store cashback request from storage.
    *
    * @param  int  $cashback
    * @param  \Illuminate\Http\Request  $request
    * 
    */
    public function storeRequest(Request $request,  int $cashback) 
    {
        /*get all json data for the coffee pods */ 
        $jrequest = json_encode($request->all());

        /*store cashback request into table cashback_request */ 
        $stored = Cashback::create([
            'request'=>$jrequest,
            'cashback'=>$cashback
        ]);

         /*return response */ 
        return $this->sendResponse($stored, 'Cashback added successfully.' );
    }
}