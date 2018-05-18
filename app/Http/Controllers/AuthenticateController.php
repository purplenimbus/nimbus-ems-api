<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Http\Requests;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Auth;
use GuzzleHttp;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Config;
use	App\Application;
use DatabaseSeeder as Seeder;

class AuthenticateController extends Controller
{
	var $mongo;
	
	public function __construct()
	{
		// Apply the jwt.auth middleware to all methods in this controller
		// except for the authenticate method. We don't want to prevent
		// the user from retrieving their token if they don't already have it
		$this->middleware('jwt.auth', ['except' => ['authenticate','linkedin','getProfile','saveProfile']]);
	   	   
		$this->seeder = New Seeder;
	}
	
	public function authenticate(Request $request)
    {
		
        $credentials = $request->only('email', 'password');
		
        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
		
		$user = Auth::user();
        // if no errors are encountered we can return a JWT
        return response()->json(compact(['token','user']));
    }
	
	/**
     * Login with LinkedIn.
     * @params Request
     */
    public function linkedin(Request $request)
    {
        $client = new GuzzleHttp\Client();
				
        $params = [
            'code' => $request->input('code'),
            'client_id' => $request->input('clientId'),
            'client_secret' => env('LINKEDIN_SECRET'),
            'redirect_uri' => $request->input('redirectUri'),
            'grant_type' => 'authorization_code',
        ];
		
		try{
			// Step 1. Exchange authorization code for access token.
			$accessTokenResponse = $client->request('POST', 'https://www.linkedin.com/uas/oauth2/accessToken', [
				'form_params' => $params
			]);
			//echo "ACCESS TOKEN \r\n";
			//var_dump($accessTokenResponse->getBody());
			
			$accessToken = json_decode($accessTokenResponse->getBody(), true);
		}catch(Exception $e){
			return response()->json(['message' => $e->getMessage()],401);
		}	
		//echo "===================================================== \r\n";
	
		//echo "PROFILE RESPONSE \r\n";
		try{
			// Step 2. Retrieve profile information about the current user.
			$profileResponse = $client->request('GET', 'https://api.linkedin.com/v1/people/~:(id,first-name,last-name,email-address,positions,picture-url,public-profile-url,industry)', [
				'query' => [
					'oauth2_access_token' => $accessToken['access_token'],
					'format' => 'json'
				]
			]);
			
			//var_dump($profileResponse);
		
			//echo "---------------------------------------------------- \r\n";
		
			$profile = json_decode($profileResponse->getBody(), true);
			
			//echo "PROFILE \r\n";
			
			//var_dump($profile);
			
			//echo "===================================================== \r\n";
				
			//create new location
			
			//create new company
			
			//save to wordpress
			
			// Step 3a. If user is already signed in then link accounts.
			if ($request->header('Authorization'))
			{
				//echo "User Signed In \r\n";
				$user = User::where('linkedin', '=', $profile['id']);
				if ($user->first())
				{
					return response()->json(['message' => 'There is already a LinkedIn account that belongs to you'], 409);
				}
				$token = explode(' ', $request->header('Authorization'))[1];
				$payload = (array) JWT::decode($token, Config::get('app.token_secret'), array('HS256'));
				$user = User::find($payload['sub']);
				$user->linkedin = $profile['id'];
				$user->fname = $user->fname ?: $profile['firstName'];
				$user->lname = $user->lname ?: $profile['lastName'];
				$user->save();
				return response()->json(['token' => $this->createToken($user)]);
			}
			// Step 3b. Create a new user account or return an existing one.
			else
			{
				//echo "User Not Signed In \r\n";
				
				
				/*$user = User::updateOrCreate(['linkedin', $profile['id']],[
											'fname' => $profile['firstName'],
											'lname'	=>	$profile['lastName'],
											'image_url'	=>	isset($profile['pictureUrl']) ? $profile['pictureUrl'] : '',
										]);*/
				
				$user = User::where('linkedin', '=', $profile['id']);
				if ($user->first())
				{
					//save to mongo
					$profile_data = $this->seeder->parse_user_profile($profile);
					
					//echo "Profile Data ".$user->first()->id."\r\n";
					
					//var_dump($profile_data);
			
					$profile_id = $this->seeder->create_or_update_profile($user->first(),$profile_data);
					
					return response()->json([ 'user' => $user->first() , 'token' => JWTAuth::fromUser($user->first()) ]);
				}else{
					$user = new User;
					$user->linkedin = $profile['id'];
					$user->fname =  $profile['firstName'];
					$user->lname =  $profile['lastName'];
					$user->image_url =  isset($profile['pictureUrl']) ? $profile['pictureUrl'] : '';
					$user->email =  isset($profile['emailAddress']) ? $profile['emailAddress'] : '';
					
					//save to mongo
					$profile_data = $this->seeder->parse_user_profile($profile);
					
					echo "Profile Data \r\n";
					
					var_dump($profile_data);
					
					$user->save();
					
					$profile_id = $this->seeder->create_or_update_profile($user,$profile_data);
					
					echo "Resume Data \r\n";
					
					var_dump($profile_id);

					return response()->json([ 'user' => $user , 'token' => JWTAuth::fromUser($user) , 'new_profile' => true ]);
				}
			}
	
		}catch(Exception $e){
			return response()->json(['message' => $e->getMessage()],401);
		}

		
	}
	
	/**
     * get user profile details
     * @params Request
     */
	public function getProfile($id,Request $request){
				
	}
	
	/**
     * save user profile details
     * @params Request
     */
	
	public function saveProfile($id,Request $request){
				
	}
	
	// somewhere in your controller
	public function getAuthenticatedUser()
	{
		try {

			if (! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json(['user_not_found'], 404);
			}

		} catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

			return response()->json(['token_expired'], $e->getStatusCode());

		} catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

			return response()->json(['token_invalid'], $e->getStatusCode());

		} catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

			return response()->json(['token_absent'], $e->getStatusCode());

		}

		// the token is valid and we have found the user via the sub claim
		return response()->json(compact('user'));
	}
}
