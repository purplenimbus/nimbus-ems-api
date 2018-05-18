<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Tenant::class, function ($faker) {
    return [
        'name' => $faker->company,
    ];
});

$factory->define(App\User::class, function ($faker) {
    return [
        'fname' => $faker->name,
        'lname' => $faker->name,
        'email' => $faker->email,
        'address' => $faker->address,
		'image_url' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQi1SYU1kgu3FtGlMpm5W7K2zuZHLgBQZzf34TQ3_Qe8LUd8s5C'
    ];
});

$factory->define(App\Task::class, function ($faker) {
    return [
        'name' => $faker->sentence(6,true),
        'description' => $faker->text(200) ,
    ];
});

$factory->define(App\Activity::class, function ($faker) {
	$types =	[
		[
			'type' => 'course',
			'action' => [
				'type' 	=> 'registration',
				'verb'	=> 'registered for'
			],
		],[
			'type' => 'content',
			'action' => [
				'type' 	=> 'like',
				'verb'	=> 'liked'
			],
		],[
			'type' => 'content',
			'action' => [
				'type' 	=> 'share',
				'verb'	=> 'shared'
			],
		]
	];
	
	$rand_type_index = mt_rand(0,sizeof($types) - 1);
	
	$data = [
        'meta' => [
			'subject' => [
				'name' => $faker->sentence(6,true),
				'url' => '#',
				'description' => $faker->text(200),
				//'images' => [],
			]
		]
    ];
	
	$data['meta']['action'] = $types[$rand_type_index]['action'];
	
	$data['meta']['subject']['type'] = $types[$rand_type_index]['type'];
	
	if($types[$rand_type_index]['action']['type'] == 'share' || $types[$rand_type_index]['action']['type'] == 'like'){	
		$data['meta']['subject']['featuredImage'] = 'https://images.pexels.com/photos/239908/pexels-photo-239908.jpeg?h=350&auto=compress&cs=tinysrgb'; 
	}
	
    return $data;
});