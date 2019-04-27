<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use phpseclib\Crypt\RSA;

class JWTGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jwt:generate {bits=512}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new JWT key pair.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $bits = $this->argument('bits');
        $rsa = new RSA();
        $key = $rsa->createKey(intval($bits));
        $path = base_path('.env');

        if (!file_exists($path)) {
            $this->error('.env not found. You will have to set the keys manually.');
            $this->error('');
            $this->line($key['privatekey']);
            $this->line('');
            $this->line($key['publickey']);

            return;
        }

        file_put_contents($path, str_replace(
            'JWT_PRIVATE_KEY="' . env('JWT_PRIVATE_KEY') . '"', 'JWT_PRIVATE_KEY="' . str_replace(["\r","\n"],'||',$key['privatekey']) . '"', file_get_contents($path)
        ));

        file_put_contents($path, str_replace(
            'JWT_PUBLIC_KEY="' . env('JWT_PUBLIC_KEY') . '"', 'JWT_PUBLIC_KEY="' . str_replace(["\r","\n"],'||',$key['publickey']) . '"', file_get_contents($path)
        ));

        $this->info('JWT keypair set successfully.');

        return;
    }
}