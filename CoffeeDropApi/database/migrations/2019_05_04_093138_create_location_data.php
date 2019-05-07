<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use League\Csv\Reader;
use App\Helper\PostCodeIO;
class CreateLocationData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('postcode');	
            $table->string('open_Monday');	
            $table->string('open_Tuesday');
            $table->string('open_Wednesday');
            $table->string('open_Thursday');
            $table->string('open_Friday');
            $table->string('open_Saturday');
            $table->string('open_Sunday');
            $table->string('closed_Monday');
            $table->string('closed_Tuesday');
            $table->string('closed_Wednesday');
            $table->string('closed_Thursday');
            $table->string('closed_Friday');
            $table->string('closed_Saturday');
            $table->string('closed_Sunday');
            $table->string('longitude');
            $table->string('latitude');
            $table->timestamps();
        });

        // Open locations data file and loop through the file line by line adding new locations.
        $file = public_path().'/csv/location_data.csv';
        $reader = Reader::createFromPath($file,'r');
        
        $reader->setHeaderOffset(0);
        //get records from csv file
        $records =$reader->getRecords();
        foreach ($records as $key=>$record) {  
            $result= PostCodeIO::get($record["postcode"]);
            $log=$result["longitude"];
            $lat=$result["latitude"];
            //store locations csv 	data into locations table													
            \DB::table('locations')->insert(array(
            'postcode' => $record["postcode"],
            'open_Monday' => $record["open_Monday"],
            'open_Tuesday' => $record["open_Tuesday"],
            'open_Wednesday' => $record["open_Wednesday"],
            'open_Thursday' => $record["open_Thursday"],
            'open_Friday' => $record["open_Friday"],
            'open_Saturday' => $record["open_Saturday"],
            'open_Sunday' => $record["open_Sunday"],
            'closed_Monday' => $record["closed_Monday"],
            'closed_Tuesday' => $record["closed_Tuesday"],
            'closed_Wednesday' => $record["closed_Wednesday"],
            'closed_Thursday' => $record["closed_Thursday"],
            'closed_Friday' => $record["closed_Friday"],
            'closed_Saturday' => $record["closed_Saturday"],
            'closed_Sunday' => $record["closed_Sunday"],
            'longitude' => $log,
            'latitude' => $lat,       
            ));
        }
      
    }

    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
