<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Lettura;
use App\Models\Contatore;
use App\Models\Impianto;
use App\Models\Importazione;
use Exception;
use Illuminate\Console\Command;

class ImportLetture extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-letture';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Legge e importa le letture dai file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->line('Import iniziato!');
        $pathFile = env('PATH_IMPORT');
        $lettureImportate = 0;

        $import = Importazione::create([
            'iniziato_alle' => now()
        ]);

        try {
            # /SAV/files/20250110/file.txt
            foreach(Impianto::all()  as $impianto) {
                $this->line('Importando letture ' . $impianto->nome . ' ...');
                $path = $pathFile . $impianto->slug . '/';

                #$path = $path.date('Ymd').'/';
                $path = $path . '20250113/';
                

                if ( file_exists($path)) {
                    foreach (scandir($path) as $file) {
                        if (!in_array($file, array(".", ".."))) {
                            $this->line('Importando ' . $path . $file . ' ...');

                            $filename = basename($file, ".txt");
                            $codiceContatore = explode('_', $filename)[0];
                            if ($codiceContatore) {
                                /* $contatore = Contatore::firstOrCreate(
                                    ['codice' => $codiceContatore],
                                    ['impianto_id' => $impianto->id] // da gestire
                                ); */
                                $contatore = Contatore::where('codice', $codiceContatore)->first();
                                if ($contatore) {

                                    #lettura file 
                                    $handle = fopen($path . $file, "r");
                                    if ($handle) {
                                        $row = 1;
                                        while (($line = fgets($handle)) !== false) {
                                            if ($row > 1) # salto la riga di intestazione
                                            {
                                                $parts = explode("\t", $line);

                                                if (count($parts) == 20) # check presenza di tutti i dati
                                                {
                                                    $dataLettura = '2025-03-05';// Carbon::createFromFormat('d/m/Y', $parts[4]);

                                                    $lettura = $contatore
                                                        ->letture()
                                                        ->whereDate('data', $dataLettura)
                                                        ->first();

                                                    if (!$lettura) // lettura non ancora importata
                                                    {
                                                        $lettura = $contatore
                                                            ->letture()
                                                            ->create([
                                                                'impianto_id'                  => $parts[2],
                                                                'tipo'                         => $parts[3],
                                                                'data'                         => $dataLettura,
                                                                'ora'                          => $parts[5],
                                                                'contatore_codice'             => $codiceContatore,
                                                                'riferimento'                  => $parts[6] ?: null,
                                                                'energia_allineata'            => $parts[7] ? floatval(str_replace(',', '.', str_replace('.', '', $parts[7]))) : null,
                                                                'energia_consumo'              => $parts[8] ? floatval(str_replace(',', '.', str_replace('.', '', $parts[8]))) : null,
                                                                'energia_potenza'              => $parts[9] ? floatval(str_replace(',', '.', str_replace('.', '', $parts[9]))) : null,
                                                                'volume_allineato'             => $parts[10] ? floatval(str_replace(',', '.', str_replace('.', '', $parts[10]))) : null,
                                                                'volume_consumo'               => $parts[11] ? floatval(str_replace(',', '.', str_replace('.', '', $parts[11]))) : null,
                                                                'portata'                      => $parts[12] ? floatval(str_replace(',', '.', str_replace('.', '', $parts[12]))) : null,
                                                                't_mandata'                    => $parts[13] ? floatval(str_replace(',', '.', str_replace('.', '', $parts[13]))) : null,
                                                                't_ritorno'                    => $parts[14] ? floatval(str_replace(',', '.', str_replace('.', '', $parts[14]))) : null,
                                                                't_diff'                       => $parts[15] ? floatval(str_replace(',', '.', str_replace('.', '', $parts[15]))) : null,
                                                                'lettura_ausiliaria_1'         => $parts[16] ? floatval(str_replace(',', '.', str_replace('.', '', $parts[16]))) : null,
                                                                'lettura_ausiliaria_2'         => $parts[17] ? floatval(str_replace(',', '.', str_replace('.', '', $parts[17]))) : null,
                                                                'lettura_ausiliaria_consumo_1' => $parts[18] ? floatval(str_replace(',', '.', str_replace('.', '', $parts[18]))) : null,
                                                                'lettura_ausiliaria_consumo_2' => $parts[19] ? floatval(str_replace(',', '.', str_replace('.', '', $parts[19]))) : null,
                                                            ]);

                                                        $lettureImportate++;

                                                        $lettura->check();
                                                    }
                                                } else {
                                                    $this->error('Riga con meno di 20 dati');

                                                    $import->errori()->create([
                                                        'file' => $path . $file,
                                                        'errore' => 'Riga con meno di 20 dati',
                                                        'dettaglio' => $line
                                                    ]);
                                                }
                                            }

                                            $row++;
                                        }

                                        fclose($handle);
                                    }
                                } else {
                                    $import->errori()->create([
                                        'file' => $path . $file,
                                        'errore' => "Contatotore non presente a sistema",
                                        'dettaglio' => "Codice contatore " . $codiceContatore
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());

            $import->errori()->create([
                'file' => $file ? $path . $file : $e->getFile(),
                'errore' => $e->getMessage(),
                'dettaglio' => $e->getTraceAsString()
            ]);
        }

        $import->update([
            'letture_importate' => $lettureImportate,
            'finito_alle' => now()
        ]);

        $this->info('Import terminato!');
        $this->line('Importate ' . $lettureImportate . ' letture');
    }
}
