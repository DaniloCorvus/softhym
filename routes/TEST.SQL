SELECT
    MT.*,
    IFNULL(TMT.Nombre, "Sin Tipo") AS Movimiento,
    (SELECT Codigo
    FROM Moneda
    WHERE Id_Moneda = '.$idMoneda.') AS Codigo_Moneda,
    (CASE WHEN (MT.Id_Tipo_Movimiento = 1 OR MT.Id_Tipo_Movimiento = 7) THEN (SELECT
    CONCAT_WS(" ", Nombres, Apellidos)
    FROM Transferencia T INNER JOIN Funcionario T2 ON T.Identificacion_Funcionario = T2.Identificacion_Funcionario
    WHERE T.Id_Transferencia = MT.Valor_Tipo_Movimiento) ELSE "" END) AS Cajero,
    (CASE WHEN (MT.Id_Tipo_Movimiento = 1 OR MT.Id_Tipo_Movimiento = 7) THEN (SELECT
    CONCAT(Forma_Pago, "/", Tipo_Transferencia)
    FROM Transferencia
    WHERE  Id_Transferencia = MT.Valor_Tipo_Movimiento)
    ELSE IFNULL(TMT.Nombre, "Sin Tipo")
     END) AS Movimiento_Alt
FROM Movimiento_Tercero MT
LEFT JOIN Tipo_Movimiento_Tercero TMT ON MT.Id_Tipo_Movimiento = TMT.Id_Tipo_Movimiento_Tercero AND MT.Id_Tercero = 9999999 
AND DATE(MT.Fecha) = '2019-10-02' HAVING Cajero LIKE '%" "%'