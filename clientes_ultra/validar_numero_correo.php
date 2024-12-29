<?php

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';

// Para SQL Server
$sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();


$resultados = $sqlServer->select("SELECT id_data, nro_documento, desc_celular, desc_celular2, desc_correo FROM data_ultra_procesado");

$cantidadActualizar = 0;
$cantidadNoActualizar = 0;
$cantidadActualizarTelefono = 0;
$cantidadActualizarCorreo = 0;
$cantidadActualizarCelular = 0;

$arrayActualizarCorreo = [];

foreach ($resultados as $fila)
{
    $actualizarCelular = false;
    $actualizarCorreo = false;
    $actualizarTelefono = false;
    
    $actualizarCelularFuente = false;
    $actualizarTelefonoFuente = false;

    $resultadosContacto = $sqlServer->select("SET NOCOUNT ON

    DECLARE @NRO_RUC VARCHAR(100) = ?
    DECLARE @CORREO VARCHAR(100), @CELULAR1 VARCHAR(100), @CELULAR2 VARCHAR(100), @ID_CLIENTE_ECOM INT

    SET @CORREO = (SELECT top 1 CTOV_EMAIL FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC AND CTOV_EMAIL IS NOT NULL ORDER BY CTOD_FECHA_ALTA desc);
    SET @CELULAR1 = (SELECT top 1 CTOV_TELEFONO_CELU FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC AND CTOV_TELEFONO_CELU IS NOT NULL ORDER BY CTOD_FECHA_ALTA desc);
    SET @CELULAR2 = (SELECT top 1 CTOV_TELEFONO_FIJO FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC AND CTOV_TELEFONO_FIJO IS NOT NULL ORDER BY CTOD_FECHA_ALTA desc);
    SET @ID_CLIENTE_ECOM = (SELECT TOP 1 CLII_ID_CLIENTE FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC);

    IF @ID_CLIENTE_ECOM IS NULL
    BEGIN
        SET @ID_CLIENTE_ECOM = (SELECT TOP 1 CLII_ID_CLIENTE FROM ECOM.ECOM_CLIENTE where CLIV_NRO_RUC = @NRO_RUC);
    END
    
    SELECT @CORREO desc_correo, @CELULAR1 desc_celular, @CELULAR2 desc_telefono, @ID_CLIENTE_ECOM id_cliente_ecom", [$fila['nro_documento']]);
    
    if(count($resultadosContacto) != 1) {
        print_r_f([$resultadosContacto, $fila]);
        exit;
    }

    $resultadosContacto = $resultadosContacto[0];
    $resultadosContactoAux = $resultadosContacto;

    $resultadosContacto['desc_correo'] = $resultadosContacto['desc_correo'] ?? 'correo.migracion.win.ultra@ultra.com';
    $resultadosContacto['desc_celular'] = $resultadosContacto['desc_celular'] ?? '999999999';
    $resultadosContacto['desc_telefono'] = $resultadosContacto['desc_telefono'] ?? '';

    if($resultadosContacto['desc_correo'] <> $fila['desc_correo'] or $resultadosContacto['desc_celular'] <> $fila['desc_celular'] or 
    $resultadosContacto['desc_telefono'] <> $fila['desc_celular2'])
    {
        if($resultadosContacto['desc_correo'] <> $fila['desc_correo']) {
            $actualizarCorreo = true;
            $arrayActualizarCorreo[] = [
                'correo_nuevo' => $resultadosContacto['desc_correo'],
                'correo_actual' => $fila['desc_correo']
            ];
        }

        if($resultadosContacto['desc_celular'] <> $fila['desc_celular']) {
            $actualizarCelular = true;
        }

        if($resultadosContacto['desc_telefono'] <> $fila['desc_celular2']) {
            $actualizarTelefono = true;
        }
    }

    if(strlen($resultadosContacto['desc_celular']) > 9 or strlen($resultadosContacto['desc_celular']) < 6 or !is_numeric($resultadosContacto['desc_celular']))
    {
        if(strlen(trim($resultadosContacto['desc_celular'])) == 9) {
            $resultadosContacto['desc_celular'] = trim($resultadosContacto['desc_celular']);
            $actualizarCelularFuente = true;
        }

        if(strlen($resultadosContacto['desc_celular']) > 9 or strlen($resultadosContacto['desc_celular']) < 6 or !is_numeric($resultadosContacto['desc_celular']))
        {
            print_r_f(['no coinciden - celular', strlen($resultadosContacto['desc_celular']), $resultadosContacto['desc_celular']]);
        }
    }

    if( strlen($resultadosContacto['desc_telefono']) > 9
    or (!is_numeric($resultadosContacto['desc_telefono']) and $resultadosContacto['desc_telefono'] <> '') )
    {
        print_r_f(['no coinciden - telefono', strlen($resultadosContacto['desc_telefono']), $resultadosContacto['desc_telefono'], $resultadosContacto]);

        $resultadosContacto['desc_telefono'] = (int) (trim($resultadosContacto['desc_telefono']));
        $actualizarTelefonoFuente = true;

        if( strlen($resultadosContacto['desc_telefono']) > 9
        or (!is_numeric($resultadosContacto['desc_telefono']) and $resultadosContacto['desc_telefono'] <> '') )
        {
            print_r_f(['no coinciden - telefono', strlen($resultadosContacto['desc_telefono']), $resultadosContacto['desc_telefono'], $resultadosContacto]);
        }
    }

    if(!filter_var($resultadosContacto['desc_correo'], FILTER_VALIDATE_EMAIL) or strlen($resultadosContacto['desc_correo']) > 43)
    {
        if(!filter_var($resultadosContacto['desc_correo'], FILTER_VALIDATE_EMAIL) or strlen($resultadosContacto['desc_correo']) > 43)
        {
            print_r_f(['no coinciden - correo', $resultadosContacto['desc_correo']]);
        }
    }

    if($actualizarCelular or $actualizarTelefono or $actualizarCorreo) {
        $cantidadActualizar++;
    } else {
        $cantidadNoActualizar++;
    }

    if($actualizarCelular) {
        $cantidadActualizarCelular++;

        $sqlServer->update("UPDATE data_ultra_procesado SET desc_celular = ? WHERE id_data = ?", [$resultadosContacto['desc_celular'], $fila['id_data']]);
    }

    if($actualizarTelefono) {
        $cantidadActualizarTelefono++;

        $sqlServer->update("UPDATE data_ultra_procesado SET desc_celular2 = ? WHERE id_data = ?", [$resultadosContacto['desc_telefono'], $fila['id_data']]);
    }

    if($actualizarCorreo) {
        $cantidadActualizarCorreo++;

        $sqlServer->update("UPDATE data_ultra_procesado SET desc_correo = ? WHERE id_data = ?", [$resultadosContacto['desc_correo'], $fila['id_data']]);
    }

    // if($actualizarCelularFuente) {
    //     print_r_f(['actualizarCelularFuente', $resultadosContacto, $resultadosContactoAux]);
    //     $sqlServer->update("UPDATE data_ultra_contacto SET CTOV_TELEFONO_CELU = ? WHERE CLIV_NRO_RUC = ?", [$resultadosContacto['desc_celular'], $fila['id_data']]);
    // }

    // print_r_f(['STOPPER', $resultadosContacto, $fila]);
}

// print_r_f($arrayActualizarCorreo);

print_r_f(['cantidadActualizar', $cantidadActualizar, 'cantidadNoActualizar', $cantidadNoActualizar, 'cantidadActualizarCelular', $cantidadActualizarCelular, 'cantidadActualizarTelefono', $cantidadActualizarTelefono, 'cantidadActualizarCorreo', $cantidadActualizarCorreo]);

print_r_f('OK :)');