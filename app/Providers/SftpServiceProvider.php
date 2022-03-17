<?php

namespace App\Providers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\PhpseclibV3\SftpConnectionProvider;
use League\Flysystem\PhpseclibV3\SftpAdapter;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;


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
           
            // dd($config['root']);
            $sftp_connection_provider = new SftpConnectionProvider(
                $config['host'],                   
                 $config['username'],
                 $config['password'],
                 null,
                 null,
                //   4222
                $config['port'],  
                false, // use agent (optional, default: false)
                30, // timeout (optional, default: 10)
                15, // max tries (optional, default: 4)
            );
            $sftp_adapter = new SftpAdapter(
                        $sftp_connection_provider,                      
                        $config['root'], // root path (required)
                        PortableVisibilityConverter::fromArray([
                            'file' => [
                                'public' => 0640,
                                'private' => 0604,
                            ],
                            'dir' => [
                                'public' => 0755,
                                'private' => 7604,
                            ],
                        ])
                    );
            $filesystem = new Filesystem($sftp_adapter );
                // dd($filesystem);
                return $filesystem;
        });
        
               
         
    }
}