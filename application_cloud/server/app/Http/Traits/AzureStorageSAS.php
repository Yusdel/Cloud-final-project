<?php

namespace App\Http\Traits;
/**
 * Generates a shared access signature for Microsoft Azure storage
 * cf. https://docs.microsoft.com/en-us/rest/api/storageservices/fileservices/constructing-an-account-sas 
 *
 * @param (accountName) The name of the Microsoft Azure storage account
 * @param (storageKey) The access key of the Microsoft Azure storage account
 * @param (signedPermissions) Required. Specifies the signed permissions for the account SAS
 * @param (signedService) Required. Specifies the signed services accessible with the account SAS
 * @param (signedResourceType) Required. Specifies the signed resource types that are accessible with the account SAS.
 * @param (signedStart) Optional. The time at which the SAS becomes valid, in an ISO 8601 format. 
 * @param (signedExpiry) Required. The time at which the shared access signature becomes invalid, in an ISO 8601 format.
 * @param (signedVersion) Required. Specifies the signed storage service version to use to authenticate requests made with this account SAS
 * @return The shared access signature encoded as base64
*/

trait AzureStorageSasTrait
{
    public function commonData(){
        return array(
            'signedResource' => 'b',
            'signedPermission' => 'r',
            'signedIdentifier' => '',
            'accountName' => env('STORAGE_ACCOUNT_NAME'),
            'accountKey' => env('STORAGE_ACCOUNT_KEY'),
            'signedVersion' => '2014-02-14',
            'rscc' => '',
            'rscd' => 'file; attachment', //Content disposition
            'rsce' => '',
            'rscl' => '',
            'rsct' => 'Image/jpg' //Content type
        );
    }

    function generateSharedAccessSignature(
        $signedStart, 
        $signedExpiry, 
        $canonicalizedResource){
        
        $cd = $this->commonData(); 
        // generate the string to sign
        $stringToSign = 
        $cd['signedPermission']."\n".
        $signedStart."\n".
        $signedExpiry."\n".
        $canonicalizedResource."\n".
        $cd['signedIdentifier']."\n".
        $cd['signedVersion']."\n".
        $cd['rscc']."\n".
        $cd['rscd']."\n".
        $cd['rsce']."\n".
        $cd['rscl']."\n".
        $cd['rsct'];

        //sign the string using hmac sha256 and get a base64 encoded version_compare
        $signature = base64_encode(
            hash_hmac(
                'sha256',
                $stringToSign,
                base64_decode($cd['accountKey']),
                true
            )
        );

        return $signature;
    }

    function getBlobUrlWithSharedAccessSignature(
        $signedStart,
        $signedExpiry,
        $signature) {

        $cd = $this->commonData();
        /* Create the signed query part */
        $arrayToUrl = [
            'sv='.urlencode($cd['signedVersion']),
            'st='.urlencode($signedStart),
            'se='.urlencode($signedExpiry),
            'sr='.urlencode($cd['signedResource']),
            'sp='.urlencode($cd['signedPermission']),
            'rscd='.urlencode($cd['rscd']),
            'rsct='.urlencode($cd['rsct']),
            'sig='.urlencode($signature)
        ];

        return $arrayToUrl;
    }

    public function getAccessPrivateUrl($startDate, $endDate, $containerName, $blobName){

        $signedStart = $startDate; //gmdate('Y-m-d\TH:i:s\Z', strtotime('-1 day'));
        $signedExpiry = $endDate; //gmdate('Y-m-d\TH:i:s\Z', strtotime('+1 day'));
        $container = $containerName;
        $blob = $blobName;
        $canonicalizedResource = '/'.env('STORAGE_ACCOUNT_NAME').'/'.$container.'/'.$blob;

        //string to signature
        $signature = $this->generateSharedAccessSignature($signedStart, $signedExpiry, $canonicalizedResource);

       //array to url
       $arrayToUrl = $this->getBlobUrlWithSharedAccessSignature($signedStart, $signedExpiry, $signature);

       $blobUrlWithSAS =  'https://'.env('STORAGE_ACCOUNT_NAME').'.blob.core.windows.net'.'/'
       .$container.'/'
       .$blob.'?'.implode('&', $arrayToUrl);

        return $blobUrlWithSAS;
    }
}