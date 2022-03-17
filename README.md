# SFTP Implementation in Laravel 
  - Install the laravel   9  
        comoser create-project laravel/laravel sftp 
  - Install jetstream 
        
        composer require laravel/jetstream

  - Install Inertia with vue.js 
        
        php artisan jetstream:install inertia --teams

  - Finalizing The Installation

        npm install  / yarn install 
        npm run prod / yarn run prod

  - Update .env file with the database parameter 

        - Go to mysql : 
            
            mysql -u roshyara -p  and create database 
            create database test;

        - Put Your parameter in .env file 

            DB_CONNECTION=mysql
            DB_HOST=127.0.0.1
            DB_PORT=3306
            DB_DATABASE=test
            DB_USERNAME=roshyara 
            DB_PASSWORD=my_password

  - Run migration
        
        php artisan migrate
 -  Add SftP login data  in .env file . The login data are from the server where you  want to send the file via sftp. 

        SFTP_HOST         ='sftp3.dhl.com'
        SFTP_PORT         = 4222
        SFTP_USERNAME     ='user_name'
        SFTP_PASSWORD     ='my_password'
        SFTP_ROOT         ='in/work/'
-   Make a Controller and a service provider  for sftp

        php artisan make:controller SftpController 
        php artisan make:provider SftpServiceProvider 

-   Install SFTP Adapter (V3) 
        
        composer require league/flysystem-sftp-v3:^3.0
        composer update 
        For more information see 
        https://flysystem.thephpleague.com/docs/adapter/sftp-v3/ 

-   Register SftpServiceProvider in the **config\app.php** file as below 

        ....
        App\Providers\JetstreamServiceProvider::class,

        /*
         * Custom   Service Providers...
         * 
         */
        App\Providers\SftpServiceProvider::class,
-   Add sftp as disk in  **config\filesystems.php** as shown sftp below

        'disks' => [
            'local' => [
                'driver' => 'local',
                'root' => storage_path('app'),
            ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],
        
        // **above two disks are already given just add the following disk**

        'sftp' => [
            'driver' => 'sftp',
            'host' => env('SFTP_HOST'),
            'port' => 4222,
            // Settings for basic authentication...
            'username' => env('SFTP_USERNAME'),
            'password' => env('SFTP_PASSWORD'),
         
            // Settings for SSH key based authentication with encryption password...
            // 'privateKey' => env('SFTP_PRIVATE_KEY'),
            // 'password' => env('SFTP_PASSWORD'),
         
            // Optional SFTP Settings...
            // 'hostFingerprint' => env('SFTP_HOST_FINGERPRINT'),
            // 'maxTries' => 4,
            // 'passphrase' => env('SFTP_PASSPHRASE'),
            
            'root' => env('SFTP_ROOT'),
            'permPublic' => 0755,
            'directoryPerm' => 0755,
            'visibility' => 'public',
            'timeout' => 30,
            'useAgent' => true,
        ],

-   Go to SftpServiceProvider and create a Filesystem as shown in the file 
        **app\Providers\SftpServiceProvider.php**

-   Go to  **routes\web.php** file to crea two  routes as following 

        Route::middleware(['auth:sanctum', 'verified'])
            ->get('/sftp/show', [SftpController::class, 'show_sftp_form'])
            ->name('sftp.show');

        Route::middleware(['auth:sanctum', 'verified'])
            ->put('/sftp/store', [SftpController::class, 'store_sftp_file'])
            ->name('sftp.store');

-   Go to app\Http\Controllers\SftpController.php and create a upload form redning
    function. 

        /**
        * 
        *File Upload function 
        *@param: null 
        *@return: Inertia rendering 
        *
        */
        public function show_sftp_form(){

            return Inertia::render('Sftp/SftpForm' , [
                    
            ]);
        }

-   Go to  **resources\js\Pages\**  do the follwoings

     -  create a folder Sftp 
     -  create a file **SftpForm.vue**
     -  create a **vue form** in the **SftpForm.vue** file
     -  run : yarn run prod / npm run prod 

-   Check the form and make a trial 

   
    - php artisan optimize 
    - php artisan key:generate
    - Run the laravel  project locally 
       **php artisan serve**
    - Go to the browser and start the laravel web 
    - Register and verify youself as user 
    - Go to the page **http://127.0.0.1:8000//sftp/show** 

-   Create a message variable for Inertia to send the seccess message 

    - Go to the middleware file : **app\Http\HandleInertiaRequests.php**
    - Write the function share as below . Add the message variable.
         public function share(Request $request)
        {
            return array_merge(parent::share($request), [
                //
                'flash' => function () use ($request) {
                    return [
                        'success' => $request->session()->get('success'),
                        'error' => $request->session()->get('error'),
                        'message' =>$request->session()->get('message')
                    ];
                },
            ]);
        }
-     Go to **app\Http\Controllers\SftpController.php** and create the following  
      two functions . The details of the two functions look at the file.

      -  public function store_sftp_file(Request $request)
      -  public function transfer_file_to_sftp_server($file)

-   Finally run the followig commands 

     php artisan optimize
     composer updpate 
     nppm run prod /yarn run prod

-   Start uploading the file you want to send your sftp server 

**end **
     



## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
