<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Flysystem\PhpseclibV3\SftpConnectionProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\PhpseclibV3\SftpAdapter;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;
use Illuminate\Support\Facades\Storage;
class SftpServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
        Storage::extend('sftp', function ($app, $config) {
             $sftp_connector_provider = new SftpConnectionProvider(
                $config['host'], // host (required)
                $config['username'], // username (required)
                $config['password'], // password (optional, default: null) set to null if privateKey is used
                null, // '/path/to/my/private_key', // private key (optional, default: null) can be used instead of password, set to null if password is set
                null, // 'my-super-secret-passphrase-for-the-private-key', // passphrase (optional, default: null), set to null if privateKey is not used or has no passphrase
                $config['port'], // port (optional, default: 22)
                false, // use agent (optional, default: false)
                30, // timeout (optional, default: 10)
                10, // max tries (optional, default: 4)
                'fingerprint-string', // host fingerprint (optional, default: null),
                null, // connectivity checker (must be an implementation of 'League\Flysystem\PhpseclibV2\ConnectivityChecker' to check if a connection can be established (optional, omit if you don't need some special handling for setting reliable connections)
            
             );
             $sftp_adapter =new SftpAdapter(
                $sftp_connector_provider,                      
                $config['root'], // root path (required)
                PortableVisibilityConverter::fromArray([
                        'file' => [
                            'public' => 0640,
                            // 'private' => 0604,
                        ],
                        'dir' => [
                            'public' => 0755,
                            // 'private' => 7604,
                        ],
                ])
            );
            // dd($config->get('root'));
            $filesystem = new Filesystem($sftp_adapter);
              
                return $filesystem;
        });
        
        
        //  ends here 
        
       
       
         
    }
}
