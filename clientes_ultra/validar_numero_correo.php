<?php
// antes del bot
require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';

// Para SQL Server
$sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();


$resultados = $sqlServer->select("SELECT id_data, nro_documento, desc_celular, desc_celular2, desc_correo
FROM data_ultra_procesado where flg_validate_celular = 0 order by id_data");

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

    $forzarActualizarCorreo = false;
    $auxCorreoAForzar = '';

    if($fila['desc_correo'] == 'correo.migracion.win.ultra@ultra.com')
    {
        $auxCorreoAForzar = $sqlServer->select("SELECT cc.CTOV_EMAIL
        FROM PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CLIENTE C
        INNER JOIN PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CLIENTE_CONTACTO CC ON CC.CLII_ID_CLIENTE = C.CLII_ID_CLIENTE
        WHERE C.CLIV_NRO_RUC = ?
        ORDER BY CC.CTOD_FECHA_ALTA DESC", [$fila['nro_documento']]);

        if(count($auxCorreoAForzar) > 0)
        {
            $primeraOpcion = '';

            foreach($auxCorreoAForzar as $correo) {
                if(filter_var($correo['CTOV_EMAIL'], FILTER_VALIDATE_EMAIL) and strlen($correo['CTOV_EMAIL']) <= 43)
                {
                    $primeraOpcion = $correo['CTOV_EMAIL'];
                    break;
                }
            }

            if($primeraOpcion != '') {
                $resultadosContacto['desc_correo'] = $primeraOpcion;
                $actualizarCorreo = true;
                // print_r_f(['auxCorreoAForzar4', $auxCorreoAForzar, 'primeraOpcion', $primeraOpcion]);
            }

            // print_r_f(['auxCorreoAForzar', $auxCorreoAForzar]);
        }
    }

    $forzarActualizarCelular = false;
    $auxCelularAForzar = '';

    if($fila['desc_celular'] == '999999999')
    {
        $auxCelularAForzar = $sqlServer->select("select * from (
            SELECT PE_OPTICAL_ADM_PORTAL.dbo.ExtractLeadingNumbers(PE_OPTICAL_ADM_PORTAL.dbo.RemoveWhitespace(cc.CTOV_TELEFONO_CELU)) desc_celular, cc.CTOD_FECHA_ALTA
            FROM PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CLIENTE C
            INNER JOIN PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CLIENTE_CONTACTO CC ON CC.CLII_ID_CLIENTE = C.CLII_ID_CLIENTE
            WHERE C.CLIV_NRO_RUC = ? AND cc.CTOV_TELEFONO_CELU IS NOT NULL
            UNION
            SELECT PE_OPTICAL_ADM_PORTAL.dbo.ExtractLeadingNumbers(PE_OPTICAL_ADM_PORTAL.dbo.RemoveWhitespace(cc.CTOV_TELEFONO_FIJO)) desc_celular, cc.CTOD_FECHA_ALTA
            FROM PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CLIENTE C
            INNER JOIN PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CLIENTE_CONTACTO CC ON CC.CLII_ID_CLIENTE = C.CLII_ID_CLIENTE
            WHERE C.CLIV_NRO_RUC = ? AND cc.CTOV_TELEFONO_FIJO IS NOT NULL
        ) A
        where A.desc_celular <> '' AND A.desc_celular <> ?
        ORDER BY A.CTOD_FECHA_ALTA DESC", [$fila['nro_documento'], $fila['nro_documento'], $fila['desc_celular2']]);

        if(count($auxCelularAForzar) > 0)
        {
            $primeraOpcion = '';

            foreach($auxCelularAForzar as $celular) {
                if(is_string($celular['desc_celular']) and strlen($celular['desc_celular']) == 9 and is_numeric($celular['desc_celular'])
                and $celular['desc_celular'][0] == '9')
                {
                    $primeraOpcion = $celular['desc_celular'];
                    break;
                }
            }

            if($primeraOpcion != '') {
                $resultadosContacto['desc_celular'] = $primeraOpcion;
                $actualizarCelular = true;
                //print_r_f(['auxCelularAForzar4', $auxCelularAForzar, 'primeraOpcion', $primeraOpcion]);
            }
            // print_r_f(['auxCelularAForzar', $auxCelularAForzar, $fila]);
        }
    }

    $forzarActualizarTelefono = false;
    $auxTelefonoAForzar = '';

    if(!$forzarActualizarCelular and !$actualizarCelular and $fila['desc_celular2'] == '')
    {
        $auxTelefonoAForzar = $sqlServer->select("select * from (
            SELECT PE_OPTICAL_ADM_PORTAL.dbo.ExtractLeadingNumbers(PE_OPTICAL_ADM_PORTAL.dbo.RemoveWhitespace(cc.CTOV_TELEFONO_CELU)) desc_celular, cc.CTOD_FECHA_ALTA
            FROM PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CLIENTE C
            INNER JOIN PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CLIENTE_CONTACTO CC ON CC.CLII_ID_CLIENTE = C.CLII_ID_CLIENTE
            WHERE C.CLIV_NRO_RUC = ? AND cc.CTOV_TELEFONO_CELU IS NOT NULL
            UNION
            SELECT PE_OPTICAL_ADM_PORTAL.dbo.ExtractLeadingNumbers(PE_OPTICAL_ADM_PORTAL.dbo.RemoveWhitespace(cc.CTOV_TELEFONO_FIJO)) desc_celular, cc.CTOD_FECHA_ALTA
            FROM PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CLIENTE C
            INNER JOIN PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CLIENTE_CONTACTO CC ON CC.CLII_ID_CLIENTE = C.CLII_ID_CLIENTE
            WHERE C.CLIV_NRO_RUC = ? AND cc.CTOV_TELEFONO_FIJO IS NOT NULL
        ) A
        where A.desc_celular <> ''
        ORDER BY A.CTOD_FECHA_ALTA DESC", [$fila['nro_documento'], $fila['nro_documento']]);

        if(count($auxTelefonoAForzar) > 0)
        {
            $todosComplenConSerCelular = true;
            $primeraOpcion = '';

            foreach($auxTelefonoAForzar as $telefono) {
                if(is_string($telefono['desc_celular']) and strlen($telefono['desc_celular']) == 9 and is_numeric($telefono['desc_celular'])
                and $telefono['desc_celular'][0] == '9')
                {
                    if($telefono['desc_celular'] <> $fila['desc_celular'])
                    {
                        $primeraOpcion = $telefono['desc_celular'];
                        break;
                    }
                }
                else {
                    $todosComplenConSerCelular = false;
                }
            }

            if(!$todosComplenConSerCelular and $primeraOpcion == '')
            {
                foreach($auxTelefonoAForzar as $telefono)
                {
                    if(is_string($telefono['desc_celular']) and strlen($telefono['desc_celular']) <= 9 and is_numeric($telefono['desc_celular']))
                    {
                        if($telefono['desc_celular'] <> $fila['desc_celular'])
                        {
                            $primeraOpcion = $telefono['desc_celular'];
                            break;
                        }
                    }
                }
                // print_r_f(['auxTelefonoAForzar2', $auxTelefonoAForzar, 'primeraOpcion', $primeraOpcion]);
            }

            if($primeraOpcion != '') {
                $resultadosContacto['desc_telefono'] = $primeraOpcion;
                $actualizarTelefono = true;
                // print_r_f(['auxTelefonoAForzar3', $auxTelefonoAForzar, 'primeraOpcion', $primeraOpcion]);
            }
        }
    }

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

        $resultadosContacto['desc_celular'] = str_replace(' ', '', $resultadosContacto['desc_celular']);

        if($resultadosContacto['desc_celular'] <> $fila['desc_celular']) {
            $actualizarCelular = true;

            $actualizarIntercambio = false;

            if($fila['desc_celular'] != str_replace(' ', '', $fila['desc_celular'])) {
                $fila['desc_celular'] = str_replace(' ', '', $fila['desc_celular']);
                $actualizarIntercambio = true;
            }
            
            if($resultadosContacto['desc_celular'] == '999999999' and strlen($fila['desc_celular']) <= 9 and
            strlen($fila['desc_celular']) >= 6 and is_numeric($fila['desc_celular'])) {
                $actualizarCelular = false;

                if($actualizarIntercambio) {
                    $resultadosContacto['desc_celular'] = $fila['desc_celular'];
                    $actualizarCelular = true;
                }
            }
        }

        $resultadosContacto['desc_telefono'] = trim($resultadosContacto['desc_telefono']);

        if($resultadosContacto['desc_telefono'] <> $fila['desc_celular2'])
        {
            $actualizarTelefono = true;

            if($resultadosContacto['desc_telefono'] == '' and strlen($fila['desc_celular2']) <= 9
            and is_numeric($fila['desc_celular2']))
            {
                $actualizarTelefono = false;
            }
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
            print_r_f(['no coinciden - celular', strlen($resultadosContacto['desc_celular']), $resultadosContacto['desc_celular'], $fila]);
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
        //print_r_f(['actualizarCelular', 'resultadosContacto' => $resultadosContacto, 'fila' => $fila]);
        $sqlServer->update("UPDATE data_ultra_procesado SET desc_celular = ? WHERE id_data = ?", [$resultadosContacto['desc_celular'], $fila['id_data']]);
    }

    if($actualizarTelefono) {
        $cantidadActualizarTelefono++;
        // print_r_f(['actualizarTelefono', 'resultadosContacto' => $resultadosContacto, 'fila' => $fila]);
        $sqlServer->update("UPDATE data_ultra_procesado SET desc_celular2 = ? WHERE id_data = ?", [$resultadosContacto['desc_telefono'], $fila['id_data']]);
    }

    if($actualizarCorreo) {
        $cantidadActualizarCorreo++;
        // print_r_f(['actualizarCorreo', $resultadosContacto, $fila]);
        $sqlServer->update("UPDATE data_ultra_procesado SET desc_correo = ? WHERE id_data = ?", [$resultadosContacto['desc_correo'], $fila['id_data']]);
    }

    // if($actualizarCelularFuente) {
    //     print_r_f(['actualizarCelularFuente', $resultadosContacto, $resultadosContactoAux]);
    //     $sqlServer->update("UPDATE data_ultra_contacto SET CTOV_TELEFONO_CELU = ? WHERE CLIV_NRO_RUC = ?", [$resultadosContacto['desc_celular'], $fila['id_data']]);
    // }

    // print_r_f(['STOPPER', $resultadosContacto, $fila]);

    $sqlServer->update("UPDATE data_ultra_procesado SET flg_validate_celular = 1, updated_at = getdate() WHERE id_data = ?", [$fila['id_data']]);
}

// print_r_f($arrayActualizarCorreo);

print_r_f(['cantidadActualizar', $cantidadActualizar, 'cantidadNoActualizar', $cantidadNoActualizar, 'cantidadActualizarCelular', $cantidadActualizarCelular, 'cantidadActualizarTelefono', $cantidadActualizarTelefono, 'cantidadActualizarCorreo', $cantidadActualizarCorreo]);

print_r_f('OK :)');