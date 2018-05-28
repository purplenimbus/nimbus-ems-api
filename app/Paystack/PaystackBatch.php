<?php
 
namespace App\Paystack;
use GuzzleHttp\Client as Guzzle;
 
class PaystackBatch {
	
	private $guzzle;
	private $endpoint;
	
	public function __construct()
    {
        $this->guzzle = new Guzzle(['headers' => ['Authorization' => 'Bearer '.env('PAYSTACK_SECRET_KEY')]]);
		
		$this->endpoint = env('PAYSTACK_PAYMENT_URL').'/bulkcharge';
    }
	/**
     * Initiate a paystack bulk charge
     *
	 * @param array $data     
	 *
     * @return Illuminate\Http\Response
     */
	public function initiateBulkCharge($data){
		
		var_dump($data);
		
		//$response = $this->guzzle->request('POST', $this->endpoint,['json' =>  $data]);
				
		//var_dump($response->getBody()->getContents());
	}
}