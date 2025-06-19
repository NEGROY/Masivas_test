<?php
header('Content-Type: application/json');

$myObj = [
    [
        "tk" => "F6131074",
        "tg_enlace" => "245700345T",
        "problem_status" => "Open",
        "ultima_actualizacion" => "carol.mantilla",
        "sysmodtime" => "13-06-2025 14:31:55",
        "tiempo_sin_seguimiento_horas" => 0.08,
        "tiempo_sin_seguimiento_f" => "0:04:43",
        "assignment" => "CNOC_CBO_REACTIVO",
        "opened_by" => "carol.mantilla",
        "open_time" => "13-06-2025 14:31:55",
        "total_horas" => 0.079,
        "tiempo_vida_tk" => "0 d 0 h 4 m",
        "total_minutos" => 4,
        "pending_customer" => 0,
        "pending_customer_1" => 0,
        "total_menos_cliente_horas" => 0.079,
        "hh_mm_ss" => "0:04:44",
        "hora_apertura" => "13-06-2025 14:31:55"
    ],
    [
        "tk" => "F6131061",
        "tg_enlace" => "317500019T",
        "problem_status" => "Work In Progress",
        "ultima_actualizacion" => "kandy.coronado",
        "sysmodtime" => "13-06-2025 14:30:23",
        "tiempo_sin_seguimiento_horas" => 0.1,
        "tiempo_sin_seguimiento_f" => "0:06:15",
        "assignment" => "GESTION N1_CBO",
        "opened_by" => "estefania.gaviria",
        "open_time" => "13-06-2025 14:27:06",
        "total_horas" => 0.159,
        "tiempo_vida_tk" => "0 d 0 h 9 m",
        "total_minutos" => 9,
        "pending_customer" => 0,
        "pending_customer_1" => 0,
        "total_menos_cliente_horas" => 0.159,
        "hh_mm_ss" => "0:09:32"
    ]
];

echo json_encode($myObj, JSON_PRETTY_PRINT);
