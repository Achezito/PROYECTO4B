<?php

require_once __DIR__ . '/../config/conection.php';

/*
Los componentes de hardware con sus atributos son:

Procesador: brand, model, speed, cores, threads, cache_memory, frecuency, power_consumption

Memoria RAM: brand, model, capacity, type, speed

Disco Duro: brand, model, capacity , type, read_speed, write_speed

GPU: brand, model, vram, frecuency, cores, power_consumption, type(para tipo de ram)

*/

class hardware {
    private $id_material;
    private $model;
    private $speed;
    private $cores;
    private $threads;
    private $cache_memory;
    private $type;
    private $capacity;
    private $hardware_type;
    private $read_speed;
    private $write_speed;
    private $brand;
    private $power_consumption;
    private $vram;
    private $frecuency;

    private static $selectHardware = "
    SELECT id_material, model, speed, cores, threads, cache_memory, tipo, capacity, read_speed, write_speed,
    brand, power_consumption, vram, frecuency, hardware_type
    FROM material_hardware WHERE hardware_type = ?;
    ";

    private static $all = "
    SELECT id_material, model, speed, cores, threads, cache_memory, tipo, capacity, read_speed, write_speed,
    brand, power_consumption, vram, frecuency, hardware_type
    FROM material_hardware;
    ";

    public function getIdMaterial() { return $this->id_material; }
    public function setIdMaterial($id_material) { $this->id_material = $id_material; }

    public function getModel() { return $this->model; }
    public function setModel($model) { $this->model = $model; }

    public function getSpeed() { return $this->speed; }
    public function setSpeed($speed) { $this->speed = $speed; }

    public function getCores() { return $this->cores; }
    public function setCores($cores) { $this->cores = $cores; }

    public function getThreads() { return $this->threads; }
    public function setThreads($threads) { $this->threads = $threads; }

    public function getCacheMemory() { return $this->cache_memory; }
    public function setCacheMemory($cache_memory) { $this->cache_memory = $cache_memory; }

    public function getType() { return $this->type; }
    public function setType($type) { $this->type = $type; }

    public function getCapacity() { return $this->capacity; }
    public function setCapacity($capacity) { $this->capacity = $capacity; }

    public function getHardwareType() { return $this->hardware_type; }
    public function setHardwareType($hardware_type) { $this->hardware_type = $hardware_type; }

    public function getReadSpeed() { return $this->read_speed; }
    public function setReadSpeed($read_speed) { $this->read_speed = $read_speed; }

    public function getWriteSpeed() { return $this->write_speed; }
    public function setWriteSpeed($write_speed) { $this->write_speed = $write_speed; }

    public function getPowerConsumption() { return $this->power_consumption; }
    public function setPowerConsumption($power_consumption) { $this->power_consumption = $power_consumption; }

    public function getVram() { return $this->vram; }
    public function setVram($vram) { $this->vram = $vram; }

    public function getFrecuency() { return $this->frecuency; }
    public function setFrecuency($frecuency) { $this->frecuency = $frecuency; }

    public function getBrand() { return $this->brand; }
    public function setBrand($brand) { $this->brand = $brand; }

    // Constructor
    public function __construct(
        $id_material,
        $model,
        $speed,
        $cores,
        $threads,
        $cache_memory,
        $type,
        $capacity,
        $read_speed,
        $write_speed,
        $brand,
        $power_consumption,
        $vram,
        $frecuency,
        $hardware_type
        ) {
        $this->id_material = $id_material;
        $this->model = $model;
        $this->speed = $speed;
        $this->cores = $cores;
        $this->threads = $threads;
        $this->cache_memory = $cache_memory;
        $this->type = $type;
        $this->capacity = $capacity;
        $this->read_speed = $read_speed;
        $this->write_speed = $write_speed;
        $this->brand = $brand;
        $this->power_consumption = $power_consumption;
        $this->vram = $vram;
        $this->frecuency = $frecuency;
        $this->hardware_type = $hardware_type;
}


public static function getAllHardware() {
    $hardware = array();
    $connection = Conexion::get_connection();
    
    if ($connection->connect_error) {
        return "Error en la conexion" . $connection->connect_error;
    }

    $command = $connection->prepare(self::$all);
    $command->execute();
    $command->bind_result(
        $id_material,
        $model,
        $speed,
        $cores,
        $threads,
        $cache_memory,
        $type,
        $capacity,
        $read_speed,
        $write_speed,
        $brand,
        $power_consumption,
        $vram,
        $frecuency,
        $hardware_type
    );

    $hardware = [];
        while ($command->fetch()) {
            $hardware[] = [
                "id_material" => $id_material,
                "model" => $model,
                "speed" => $speed,
                "cores" => $cores,
                "threads" => $threads,
                "cache_memory" => $cache_memory,
                "type" => $type,
                "capacity" => $capacity,
                "read_speed" => $read_speed,
                "write_speed" => $write_speed,
                "brand" => $brand,
                "power_consumption" => $power_consumption,
                "vram" => $vram,
                "frecuency" => $frecuency,
                "hardware_type" => $hardware_type
            ];
        }

        return $hardware;
}


public static function getHardware($type_hardware) {
    $hardware = array();
    $connection = Conexion::get_connection();
    
    if ($connection->connect_error) {
        return "Error en la conexion" . $connection->connect_error;
    }

    $command = $connection->prepare(self::$selectHardware);
    $command->bind_param('s', $type_hardware);
    $command->execute();
    $command->bind_result(
        $id_material,
        $model,
        $speed,
        $cores,
        $threads,
        $cache_memory,
        $type,
        $capacity,
        $read_speed,
        $write_speed,
        $brand,
        $power_consumption,
        $vram,
        $frecuency,
        $hardware_type
    );

    switch ($type_hardware) {
        case 'processor':
            while ($command->fetch()) {
                array_push($hardware, new hardware(
                    $id_material,
                    $model,
                    $speed,
                    $cores,
                    $threads,
                    $cache_memory,
                    $type=null,
                    $capacity=null,
                    $read_speed=null,
                    $write_speed=null,
                    $brand,
                    $power_consumption,
                    $vram=null,
                    $frecuency,
                    $hardware_type
                ));
            }
        break;

        case 'ram':
            while ($command->fetch()) {
                array_push($hardware, new hardware(
                    $id_material,
                    $model,
                    $speed,
                    $cores=null,
                    $threads=null,
                    $cache_memory=null,
                    $type,
                    $capacity,
                    $read_speed=null,
                    $write_speed=null,
                    $brand,
                    $power_consumption=null,
                    $vram=null,
                    $frecuency=null,
                    $hardware_type
                ));
            }
        break;

        case 'hard disc':
            while ($command->fetch()) {
                array_push($hardware, new hardware(
                    $id_material,
                    $model,
                    $speed=null,
                    $cores=null,
                    $threads=null,
                    $cache_memory=null,
                    $type,
                    $capacity,
                    $read_speed,
                    $write_speed,
                    $brand=null,
                    $power_consumption=null,
                    $vram=null,
                    $frecuency=null,
                    $hardware_type
                ));
            }
        break;

        case 'gpu':
            while ($command->fetch()) {
                array_push($hardware, new hardware(
                    $id_material,
                    $model,
                    $speed=null,
                    $cores,
                    $threads=null,
                    $cache_memory=null,
                    $type=null,
                    $capacity=null,
                    $read_speed=null,
                    $write_speed=null,
                    $brand,
                    $power_consumption,
                    $vram,
                    $frecuency,
                    $hardware_type
                ));
            }
        break;
    }

    mysqli_stmt_close($command);
    $connection->close();

    return $hardware;
}


}
