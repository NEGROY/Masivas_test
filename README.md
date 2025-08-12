# masiva_test
principal prueba para automatizar el uso de las masivas 


-  con el boton consumir la api 
que traiga con la falla, el 
"tk":"F6131074"  "total_horas":0.079
"total_menos_cliente_horas" => 0.079,

- que al seleccionar el boton del area de escalacion se muetre la tabla de escalacion

- se debe de calcular cuantas horas y en que etapa de la masiva se encuentra

- tomar en cunenta el valor de las horas. 

17/06/2025
- paginar la tabla de las areas, de escalacion. 

- aregar un select2 donde ( en vez de las tablas )
https://select2.org/getting-started/installation

- una falla solo puede tener una tabla de escalacion 
- agrgar un icono si a escalacion es por WHAPP


-------------------------------------------------

☑ UN BOTON para copiar los mensajes o un metodo para copiarlos.      
☑ obtener la HORA ACTUAL DESDE LA API, "open_time" => "13-06-2025 14:31:55" Y PEGARLA EN EL INPUT
☑ AGREGAR TOOLTIP EN LOS BOTONES DE LAS TABLAS 
- AGREGAR UN WIDTH AUTO EN LOS TEXT AREA
- LOADER EN LOS BOTONES DE BUSCAR Y DEL CALCULAR
- insert de la tabla para hacer las pruebas del tablero 

- CREAR LA VISTA DEL TABLERO
- VALIDAR EL SEMAFORO PARA EL TABLERO DE (15 10 5 >MAYOR A LOS MINUTOS)


- CREAR UNA VISTA PARA LLEVAR UN REGISTRO DE LA FALLA Y ALMACENAR DATOS DE LAS FALLAS ASOCIADAS 
(PE, WAN, VRF, ID_SERVIVIO ) GUARDAR MANUALMENTE, 
- VALIDAR CON EL ID SI PERTENCE AL MISMO PAIS (T, SV, ON, OH , CR, )

- ----------------------------------------------------

 ESTE ES PARA LISTAR
http://172.20.97.102:8000/masivas?token=masivas2025
BUSCAR INFORMACION MASIVA
http://172.20.97.102:8000/masivas/F6144046?token=masivas2025
http://172.20.97.102:8000/masivas/list/F5875158?token=masivas2025
http://172.20.97.102:8000/connection
VALIDAR CONECXION

23/06/24
- ----------------------------------------------------

- en el tablero un boton para ir a ver la falla masiva ASOCIADA. 
- UN INPUT HIDDEN, para quien actualizo de ultimo la falla
- MANDAR EL VALOR AL SELECT ANIDADO
- 
☑ agregar un loader en las fallas asociadas (FALTA EN EL en index )


- CRON JOB, (API) para actualizar info 
- AGREGAR UNA VALIDACION DE LA OPERATIVIDAD DE LA API 
- SE REQUIERE QUE SIEMPRE SE TENGA ACTUALIZADA LA DB, SOBRE QUIEN FUE EL ULTIMO EN ACTUALIZAR LA FALLAS, 
Y QUE TIEMPO ACUMULADO LLEVA 


☑ PRACTICAMENTE CAMBIAR TODO EL TABLERO, UNA BARRA DONDE ESTEN TODAS LAS FALLAS, 15 MIN AHORA QUE SEAN EXCLUSIVAMNETE DE <15 MIN  DE 15 - 10 MIN >10 MIN 
☑ (En una de las vistas se debe de realizar las, recargar cada 2 min, ademas que se recargue al momento de un insert, puedes solo validar la hora mas peque;a, y si esta esta cerca de los 15 min para que se recargue la pagina )



PARA LAS FALLAS MASIVAS.
☑ crear la BD de aociados.
☑ al buscar la falla, que obtenga todas las asociadas.
☑ validar una por una para realizar el insert
☑ AGREGAR LOS CAMPOS DE WAN, VRF Y PE. 
- COLOCAR BOTON DEINSERT 
- AGREGAR LOS VALORES AL MOSTRAR LA TABLA 
- VALIDAR LA SINTAXIS DE LAS IP Y VRF (sin espacios y signos especiales)
- reaLIZAR DESCARGAS DE EXCEL DE LAS FALLAS ASOCIADAS.   
