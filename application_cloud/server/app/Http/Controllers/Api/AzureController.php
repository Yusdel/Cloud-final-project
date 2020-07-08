<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Traits\AzureStorageSasTrait;
use App\Http\Traits\AzureTrait;
use MicrosoftAzure\Storage\Blob\Models\Blob;
use MicrosoftAzure\Storage\Blob\Models\GetBlobResult;

class AzureController extends Controller
{
    use AzureTrait, AzureStorageSasTrait;
    ## adds file to the storage. Usage: storageAddFile("myContainer", "C:\path\to\file.png", "filename-on-storage.png")
    /**
     * Upload files on Blob storage
     * @param [object] file
     * @return [string] URL
     */
    public function store(Request $request){

        $containerName = $request->user()->containerName;

        if ($request->hasFile('image')) {
            $fileToUpload = $request->file('image');
            $fileName = Str::lower(request('image_name')) . '.' . $fileToUpload->getClientOriginalExtension();
        }

        $this->storeImageOnContainer($containerName, $fileToUpload, $fileName);

        //build response exception
        // if(!$res = $this->storeImageOnContainer($containerName, $fileToUpload, $fileName))
        //     return response()->json($res, 424);
        
        return response()->json("Resource loaded!", 201);
    }

    /**
     * Analize photo
     * param [string] URL
     * @return [object] Json
     */
    public function analizeImage(Request $request){
        //get container name and name image to get url img to analize
        $containerName = $request->user()->containerName;
        $image_name = $request['image_name'];

        $url_to_analize = $this->getBlobUrlToAnalize($containerName, $image_name);

        //$url_img = $request->url_image;
        
        $res = $this->analizator($url_to_analize);

        return response()->json($res);
        
    }

    /**
     * Delete image
     * param [string] name blob
     * @return [void]
     */
    public function deleteImage(Request $request){
        //get container name and name image to get url img to analize
        $containerName = $request->user()->containerName;
        $image_name = $request['image_name'];

        $res = $this->deleteBB($containerName, $image_name);

        return response()->json($res);
        
    }

    /**
     * get list blobs in container
     * @param [string] $containerName
     * @return [object] list name and uri of blob in container 
     */
    public function getListBlob(Request $request){
        $containerName = $request->user()->containerName;

        $startDate = gmdate('Y-m-d\TH:i:s\Z', strtotime('+0 day'));
        $endDate = gmdate('Y-m-d\TH:i:s\Z', strtotime('+1 day'));

        $res = $this->getAllBlobs($containerName, $startDate, $endDate);
        return response()->json($res);
    }

    /**
     * get url shared image
     * @param void
     * @return [string] user delegate key 
     */
    public function sharedImage(Request $request){
        $containerName = $request->user()->containerName;
        $blobName = request('nome_image');
        $days = request('numero_giorni');
        $startDate = gmdate('Y-m-d\TH:i:s\Z', strtotime('+0 day'));
        $endDate = gmdate('Y-m-d\TH:i:s\Z', strtotime('+'.$days.' day'));

        $res = $this->getAccessPrivateUrl($startDate, $endDate, $containerName, $blobName);

        return response()->json($res);
    }
}
