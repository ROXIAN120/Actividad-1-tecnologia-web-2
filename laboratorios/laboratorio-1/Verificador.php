<?php
declare(strict_types=1);

final class Verificador
{
    /** @var resource|null */
    private $proceso = null;
    private int $puerto = 0;
    private int $correctas = 0;
    private int $total = 0;

    public function servidor(string $directorioPublico): void
    {
        $socket = stream_socket_server('tcp://127.0.0.1:0', $codigo, $mensaje);
        if ($socket === false) {
            throw new RuntimeException("No se pudo reservar un puerto: $mensaje ($codigo)");
        }

        $direccion = stream_socket_get_name($socket, false);
        fclose($socket);
        $this->puerto = (int) substr(strrchr((string) $direccion, ':'), 1);

        $registro = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'tw2-verificador-' . getmypid() . '.log';
        $descriptores = [
            0 => ['pipe', 'r'],
            1 => ['file', $registro, 'a'],
            2 => ['file', $registro, 'a'],
        ];
        $comando = [PHP_BINARY, '-S', "127.0.0.1:{$this->puerto}", '-t', $directorioPublico];
        $this->proceso = proc_open($comando, $descriptores, $tuberias, dirname($directorioPublico));

        if (!is_resource($this->proceso)) {
            throw new RuntimeException('No se pudo iniciar el servidor integrado de PHP.');
        }
        fclose($tuberias[0]);

        $limite = microtime(true) + 5;
        do {
            $conexion = @fsockopen('127.0.0.1', $this->puerto, $codigo, $mensaje, 0.2);
            if (is_resource($conexion)) {
                fclose($conexion);
                return;
            }
            usleep(100_000);
        } while (microtime(true) < $limite);

        throw new RuntimeException('El servidor integrado no respondió a tiempo.');
    }

    /** @return array{estado:int,cabeceras:string,cuerpo:string} */
    public function pedir(string $ruta): array
    {
        $contexto = stream_context_create([
            'http' => [
                'ignore_errors' => true,
                'timeout' => 5,
            ],
        ]);
        $cuerpo = @file_get_contents("http://127.0.0.1:{$this->puerto}{$ruta}", false, $contexto);
        $cabecerasLista = $http_response_header ?? [];
        $cabeceras = implode("\r\n", $cabecerasLista);
        $estado = 0;
        if (isset($cabecerasLista[0]) && preg_match('/\s(\d{3})\s/', $cabecerasLista[0], $coincidencia)) {
            $estado = (int) $coincidencia[1];
        }

        return [
            'estado' => $estado,
            'cabeceras' => $cabeceras,
            'cuerpo' => $cuerpo === false ? '' : $cuerpo,
        ];
    }

    public function revisar(string $descripcion, callable $comprobacion): void
    {
        $this->total++;
        try {
            $correcta = (bool) $comprobacion();
        } catch (Throwable $error) {
            $correcta = false;
        }

        if ($correcta) {
            $this->correctas++;
            echo "✅ $descripcion", PHP_EOL;
        } else {
            echo "❌ $descripcion", PHP_EOL;
        }
    }

    public function fin(): int
    {
        echo "Resultado: {$this->correctas} de {$this->total} comprobaciones", PHP_EOL;
        $this->detenerServidor();
        return $this->correctas === $this->total ? 0 : 1;
    }

    public function __destruct()
    {
        $this->detenerServidor();
    }

    private function detenerServidor(): void
    {
        if (is_resource($this->proceso)) {
            proc_terminate($this->proceso);
            proc_close($this->proceso);
            $this->proceso = null;
        }
    }
}
