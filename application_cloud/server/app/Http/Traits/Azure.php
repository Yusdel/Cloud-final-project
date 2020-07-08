<?php

namespace App\Http\Traits;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
use MicrosoftAzure\Storage\Blob\Models\SetBlobPropertiesOptions;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;

trait AzureTrait
{
    use AzureStorageSasTrait;
    
    private function getConnectionString(){
        return 'DefaultEndpointsProtocol=https;AccountName='.env('STORAGE_ACCOUNT_NAME').';AccountKey='.env('STORAGE_ACCOUNT_KEY');
    }
    
    /**
    * Create Personal Container on Azure
    * @param [integer] userID
    * @return [string] container name
    */
    public function createUserContainer($userID){
        /**
         * name of blob container for user
         * must be >= 3 chars
         */
        $userID < 100 ? 
        ($userID < 10 ? $containerName = '00'.(string)$userID : $containerName = '0'.(string)$userID)
        : $containerName = (string)$userID;

         # Setup a specific instance of an Azure::Storage::Client
         $connectionString = "DefaultEndpointsProtocol=https;AccountName=".env('STORAGE_ACCOUNT_NAME').";AccountKey=".env('STORAGE_ACCOUNT_KEY');
    
         // Create blob client.
         $blobClient = BlobRestProxy::createBlobService($connectionString);
     
         # Create the BlobService that represents the Blob service for the storage account
         $createContainerOptions = new CreateContainerOptions();
         
         $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
         
         // Set container metadata.
         // $createContainerOptions->addMetaData("key1", "value1");
         // $createContainerOptions->addMetaData("key2", "value2");
 
         // Create container.
         try{$blobClient->createContainer($containerName, $createContainerOptions);
         }catch(Exception $error){return Response::make($error ,400);}

         return $containerName;
    }

    /**
     * Store Image in userContainer
     * @param 
     * @return void
     */

    public function storeImageOnContainer($containerName, $fileToUpload, $fileName){
        $conn = $this->getConnectionString();

        //set options blob content type
        //$contentType = getContentType($fileToUpload) da implementare!!!
        $contentType = 'image/jpg';
        $options = new CreateBlockBlobOptions();
        $options->setContentType($contentType);

        // Create blob client.
        $blobClient = BlobRestProxy::createBlobService($conn);
        $content = fopen($fileToUpload, "r");

        //maybe the try catch block is not necessary, the sdk check errors
        $blobClient->createBlockBlob($containerName, $fileName, $content, $options);
    }

     /**
     * Store Image in userContainer
     * @param 
     * @return void
     */

    public function getAllBlobs($containerName, $startDate, $endDate){

        try {
            // List blobs.
            $blob_list_result = [];
            $listBlobsOptions = new ListBlobsOptions();
            $conn = $this->getConnectionString();
            $listBlobsOptions->setMaxResults(1);

            // Create blob client.
            $blobClient = BlobRestProxy::createBlobService($conn);

            do {
                $blob_list = $blobClient->listBlobs($containerName, $listBlobsOptions);

                foreach ($blob_list->getBlobs() as $blob) {
                    $url = $this->getAccessPrivateUrl($startDate, $endDate, $containerName, $blob->getName());
                    $blob_list_result[] = array("name"=>$blob->getNAme(), "url"=>$url); //[$blob->getName()] = $url;//$blob->getUrl().PHP_EOL;
                }

                $listBlobsOptions->setContinuationToken($blob_list->getContinuationToken());
            } while ($blob_list->getContinuationToken());

        }catch (ServiceException $e){
            $code = $e->getCode();
            $error_message = $e->getMessage();
            return array(
                "code"=>$code,
                "error_message"=>$error_message
            );
        }

        return $blob_list_result;
    }

    /**
     * Analize Image with Azure Computer Vision
     * @param [String] URL (about image in container)
     * @param [string] name (container)
     * @return [Object] json 
     */

    public function analizator($url_img){

        /**
         * we can get : tag, analyze, description, detect etc.
         * but it's must insert in url after v3.0/
         */
        $url = env('ENDPOINT').'/vision/v3.0/tag'; //?visualFeature=Categories,Description&details=Landmarks&language=en';
        //$data = array('url'=> $url_img);

        $response = Http::withHeaders([
            'Content-type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => env('API_KEY_1_COMPUTER_VISION'),
            'language' => 'en',
            'visualFeature'=> 'description',
            'details'=>'Landmarks'
            ])->post($url, [
                'url' => $url_img,
            ]);

        return $response->json();
    }

    /**
     * Delete Image
     * @param [String] image name
     * @return [success]
     */
    public function deleteBB($containerName, $blobName){
        $container = $containerName;
        $blob_name = $blobName;
        $conn = $this->getConnectionString();
        // Create blob client.
        $blobClient = BlobRestProxy::createBlobService($conn);

        $blob = $blobClient->deleteBlob($container, $blob_name);

        //status code of operation
        return $blob;
    }
    
    public function getPrivateUrlBlob($containerName){

        $startDate = gmdate("Y-m-d\TH:i:s\Z");
        $endDate = gmdate("Y-m-d\TH:i:s\Z")+3000;
        $blobName = "tokyo.jpg";
        return $startDate;
        // $url = env('ENDPOINT').'/?restype=service&comp=userdelegationkey';
        // //request GET delegate access key
        // $response = Http::withHeaders([
        //     'Content-type' => 'application/json',
        //     'Ocp-Apim-Subscription-Key' => env('API_KEY_1_COMPUTER_VISION'),
        //     'language' => 'en',
        //     'visualFeature'=> 'description',
        //     'details'=>'Landmarks'
        //     ])->post($url, [
        //         'url' => $url_img,
        //     ]);
    }

    //GET single blob url to analize
    function getBlobUrlToAnalize($containerName, $blobName){
        $container = $containerName;
        $blob_name = $blobName;
        $conn = $this->getConnectionString();
        // Create blob client.
        $blobClient = BlobRestProxy::createBlobService($conn);

        $blob = $blobClient->getBlobUrl($container, $blob_name);

        return $blob;
    }
}