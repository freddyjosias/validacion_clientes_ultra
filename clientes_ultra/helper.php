<?php

function convertStatusToDescription($status) {
    if ($status == 'Sin Conformidad') {
        $status = 'Activo';
    }

    return $status;
}

function convertServDescriptionToFinalDescription($servDescription, $esMPLS)
{
    $prodDescription = null;

    if($servDescription == 'Ultra 1000' and $esMPLS)
    {
        return ['desc_servicio' => 'Migración Ultra 1000 - MPLS', 'desc_producto' => 'Migración Ultra 1000 Mbps - MPLS'];
    }
    else if($servDescription == 'Ultra 600' and $esMPLS)
    {
        return ['desc_servicio' => 'Migración Ultra 600 - MPLS', 'desc_producto' => 'Migración Ultra 600 Mbps - MPLS'];
    }
    else if($servDescription == 'Ultra 800' and $esMPLS)
    {
        return ['desc_servicio' => 'Migración Ultra 800 - MPLS', 'desc_producto' => 'Migración Ultra 800 Mbps - MPLS'];
    }
    else if($servDescription == 'Ultra 600' and !$esMPLS)
    {
        return ['desc_servicio' => 'Migración Ultra 600', 'desc_producto' => 'Migración Ultra 600 Mbps'];
    }

    print_r_f(['desc_servicio' => $servDescription, 'desc_producto' => $prodDescription]);

    return ['desc_servicio' => $servDescription, 'desc_producto' => $prodDescription];
}

function convertAnchoBandaToMbpsGbps($anchoBanda) {
    if ($anchoBanda == '1000' or $anchoBanda == '1000000.00' or $anchoBanda == '1000.00') {
        return '1 Gbps';
    } else if ($anchoBanda == '10000') {
        return '10 Gbps';
    } else if ($anchoBanda == '100000') {
        return '100 Gbps';
    } elseif ($anchoBanda == '800' or $anchoBanda == '800000.00') {
        return '800 Mbps';
    } else if ($anchoBanda == '600' or $anchoBanda == '600000.00') {
        return '600 Mbps';
    } else if ($anchoBanda == '10') {
        return '10 Mbps';
    } else if ($anchoBanda == '1') {
        return '1 Mbps';
    } else {
        return $anchoBanda;
    }
}

function validateOfertaXVelocidad($oferta, $velocidad) {

    if($oferta == 'Ultra 1000' and $velocidad == '1 Gbps') {
        return true;
    } else if(($oferta == 'Ultra 600' or $oferta == 'Ultra 1000') and $velocidad == '600 Mbps') {
        return true;
    } else if($oferta == 'Ultra 600' and $velocidad == '600 Mbps') {
        return true;
    } else if($oferta == 'Ultra 1000' and $velocidad == '800 Mbps') {
        return true;
    } else if($oferta == 'Ultra 800' and ($velocidad == '800 Mbps' or $velocidad == '1 Gbps')) {
        return true;
    } else if($oferta == 'Ultra 600' and $velocidad == '1 Gbps') {
        return true;
    }

    return false;
}
