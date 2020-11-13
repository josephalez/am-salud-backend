DELIMITER $$

DROP TRIGGER IF EXISTS balance_creditos_saldo_udate;

$$

-- Crear el disparador que suma o resta el saldo en los movimientos 
CREATE TRIGGER balance_creditos_saldo_udate AFTER UPDATE ON movimiento_creditos 
FOR EACH ROW 
    BEGIN 
        UPDATE `balance_creditos` SET `saldo` = saldo+(NEW.monto-OLD.monto) WHERE `balance_creditos`.`id` = NEW.balance_id;
    END;
$$

DROP TRIGGER IF EXISTS balance_creditos_saldo_insert;

$$ 

CREATE TRIGGER balance_creditos_saldo_insert AFTER INSERT ON movimiento_creditos
FOR EACH ROW
BEGIN
    UPDATE `balance_creditos` SET `saldo` = saldo+NEW.monto WHERE `balance_creditos`.`id` = NEW.balance_id;
END;

$$


