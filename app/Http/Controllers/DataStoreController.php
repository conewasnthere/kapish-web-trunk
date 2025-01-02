<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Models\DataStore;

class DataStoreController extends Controller
{
    // handles DataStores for 2014
    // this will be annoying
    // turns out it wasn't that annoying
    // implement ordered datastores & old persistence next

    public int $CLIENT_MAX_LENGTH = 65536;

    function getAsync(Request $request)
    {
        if ($request->giveMeAKey != config('app.rcc_key')) {
            abort(403);
        }

        // GetAsync
        // POST DATA:
        // &qkeys[0].scope=global&qkeys[0].target=KEY&qkeys[0].key=DATASTORE
        // /persistence/getV2?placeId=1&type=standard&scope=global

        // parse whatever the fuck is going on with our post data lol

        $postData = $request->getContent();
        $postData = array_filter(explode('&', $postData));

        $parsedData = collect([]);
        foreach($postData as $key)
        {
            // horrible
            $value = substr($key, strpos($key, '.') + 1);
            $newvalue = substr($value, strpos($value, '=') + 1);
            $newkey = substr($value, 0, strpos($value, '='));

            $parsedData->put($newkey, $newvalue);
        }

        // check if datastore exists        
        $datastore = DataStore::where([
            'datastore' => urldecode($parsedData['key']),
            'pid' => $request->get('placeId'),
        ])->first();

        if($datastore)
        {
            // now we try to get the key that it wants
            $keys = collect(json_decode($datastore->keys));
            $key = $keys->get(urldecode($parsedData['target']));
            
            if(!$key) {
                return json_encode(
                    array(
                        "data" => array(),
                    )
                );
            }

            $result = stripslashes(
                json_encode(
                    array(
                    "data" =>
                        array([
                            $parsedData['key'] =>
                            array(                                
                                "Scope" => $parsedData['scope'],
                                "Target" => urldecode($parsedData['target']),
                                "Key" => $parsedData['key']
                            ),
                            "Value" => (json_decode($key) ? addslashes($key) : addslashes(sprintf('"%s"', $key))),
                        ]),
                    )
                ));
            return $result;
        } else 
        {
            // we will have to create this datastore. how fun
            $datastore = new DataStore;
            $datastore->datastore = urldecode($parsedData['key']);
            $datastore->pid = $request->get('placeId');
            $datastore->keys = '{}';
            $datastore->save();
        }
    }

    function setAsync(Request $request)
    {
        // SetAsync
        // POST DATA:
        // value=%22KEYVALUE%22
        // /persistence/set?placeId=1&key=DATASTORE&&type=standard&scope=global&target=KEY&valueLength=VALUELENGTH

        // thankfully this is less shit to parse
        if ($request->giveMeAKey != config('app.rcc_key')) {
            abort(403);
        }
        $postData = $request->getContent();
        $values = json_decode(urldecode(substr($postData, strpos($postData, '=') + 1)));

        // check if datastore exists        
        $datastore = DataStore::where([
            'datastore' => urldecode($request->get('key')),
            'pid' => $request->get('placeId'),
        ])->first();

        if(!$datastore)
        {
            // we will have to create this datastore. how fun
            $datastore = new DataStore;
            $datastore->datastore = urldecode($request->get('key'));
            $datastore->pid = $request->get('placeId');
            $datastore->keys = collect([])->put($request->get('target'), $values);
            $datastore->save();
        }
        
        // add or update this key
        // let's check for key length here
        // if someone patches out the native roblox length check, they can insert a shitton amount of data in the db
        // fix this by rewrting everything later
        /* if(strlen(strval($values)) > $this->CLIENT_MAX_LENGTH) {
            $result = json_encode(array("error" => array()));
            return $result;
        } */
        
        $keys = collect(json_decode($datastore->keys));
        $keys->put($request->get('target'), $values);
        $datastore->update(['keys' => $keys]);

        $result = json_encode(array("data" => array()));
        return $result;
    }
}
