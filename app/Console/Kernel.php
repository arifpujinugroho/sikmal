<?php

namespace App\Console;

use File;
use App\Model\FailUp;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function () {
        
        //cek everyday apakah ada yang gagal upload ke drive    
        $cekpemilikpkm = FaliUp::select('*')->count();

            if($cekpemilikpkm > 0){
                    $fail = FailUp::select('*')->first();
                    // untuk yang gagal foto
                    if($fail->tipe == "Foto"){ 
                        try{
                            $nama_file = $fail->namafile;
                            $fileData = File::get($fail->dir);
                            $dir = '/';
                            $recursive = false; // Get subdirectories also?
                            $contents = collect(Storage::cloud()->listContents($dir, $recursive));
                            $dir = $contents->where('type', '=', 'dir')
                                ->where('filename', '=', 'foto')
                                ->first(); // There could be duplicate directory names!
                            if ( ! $dir) {
                                return 'Directory does not exist!';
                            }
                            Storage::cloud()->put($dir['path'].'/'.$nama_file, $fileData);

                            $delfoto = FailUp::whereid($fail->id)->delete();
                        } catch (\Exception $e) {
                            //
                        }

                    } elseif ($fail->tipe == "Proposal") {
                        try {                        
                            $nama_file = $fail->namafile;
                            $fileData = File::get($fail->dir);
                            $tahun_aktif = $fail->tambahan;

                            $dir = '1AN8aGPkXdH5ZKvUhyAVvc-4GCFY6q0ly/'; //id file drive proposal
                            $recursive = false; // Get subdirectories also?
                            $contents = collect(Storage::cloud()->listContents($dir, $recursive));
                            $dir1 = $contents->where('type', '=', 'dir')
                                ->where('filename', '=', $tahun_aktif)
                                ->first(); // There could be duplicate directory names!
                            if ( ! $dir1) {
                                $coba = Storage::cloud()->makeDirectory('1AN8aGPkXdH5ZKvUhyAVvc-4GCFY6q0ly/'.$tahun_aktif);
                                $contents2 = collect(Storage::cloud()->listContents($dir, $recursive));
                                $dir2 = $contents2->where('type', '=', 'dir')
                                ->where('filename', '=', $tahun_aktif)
                                ->first();
                                Storage::cloud()->put($dir2['path'].'/'.$nama_file, $fileData);
                                $delproposal = FailUp::whereid($fail->id)->delete();
                            }else{
                                Storage::cloud()->put($dir1['path'].'/'.$nama_file, $fileData);
                                $delproposal = FailUp::whereid($fail->id)->delete();
                            }
                        } catch (\Exception $e) {
                            //
                        }
                    }

            }

        })->hourly();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
