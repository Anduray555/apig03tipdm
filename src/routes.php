<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/', function (Request $request, Response $response) {
    $payload = [
        "status" => "ok",
        "message" => "API de Seguridad Personal en funcionamiento"
    ];
    $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

$app->get('/health', function (Request $request, Response $response) {
    $payload = [
        "status" => "ok",
        "message" => "API funcionando correctamente"
    ];
    $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

$app->get('/api/zonas-riesgo', function (Request $request, Response $response) {
    try {
        $pdo = \App\DB\Conexion::getConnection();

        $sql = "SELECT id_zona, nombre, descripcion, departamento, municipio, categoria, latitud, longitud, radio_metros, nivel_riesgo, activa
                FROM zonas_riesgo
                WHERE activa = 1
                ORDER BY id_zona ASC";

        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll();

        $data = array_map(function ($row) {
            return [
                "idZona" => (int)$row["id_zona"],
                "nombre" => $row["nombre"],
                "descripcion" => $row["descripcion"],
                "departamento" => $row["departamento"],
                "municipio" => $row["municipio"],
                "categoria" => $row["categoria"],
                "latitud" => (float)$row["latitud"],
                "longitud" => (float)$row["longitud"],
                "radioMetros" => (int)$row["radio_metros"],
                "nivelRiesgo" => $row["nivel_riesgo"],
                "activa" => ((int)$row["activa"]) === 1
            ];
        }, $rows);

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Throwable $e) {
        $payload = [
            "error" => "No se pudieron cargar las zonas de riesgo",
            "detalle" => $e->getMessage()
        ];
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

$app->get('/api/servicios-emergencia', function (Request $request, Response $response) {
    try {
        $pdo = \App\DB\Conexion::getConnection();

        $sql = "SELECT id_servicio, nombre, telefono, tipo_servicio, departamento, municipio, direccion, latitud, longitud, activo
                FROM servicios_emergencia
                WHERE activo = 1
                ORDER BY id_servicio ASC";

        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll();

        $data = array_map(function ($row) {
            return [
                "idServicio" => (int)$row["id_servicio"],
                "nombre" => $row["nombre"],
                "telefono" => $row["telefono"],
                "tipoServicio" => $row["tipo_servicio"],
                "departamento" => $row["departamento"],
                "municipio" => $row["municipio"],
                "direccion" => $row["direccion"],
                "latitud" => (float)$row["latitud"],
                "longitud" => (float)$row["longitud"],
                "activo" => ((int)$row["activo"]) === 1
            ];
        }, $rows);

        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Throwable $e) {
        $payload = [
            "error" => "No se pudieron cargar los servicios de emergencia",
            "detalle" => $e->getMessage()
        ];
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

$app->get('/test-db', function (Request $request, Response $response) {
    try {
        $pdo = \App\DB\Conexion::getConnection();
        $payload = ["status" => "ok", "message" => "Conexion BD exitosa"];
    } catch (Throwable $e) {
        $payload = ["status" => "error", "message" => $e->getMessage()];
    }

    $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});