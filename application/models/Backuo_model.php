<?php
Class Backuo_model extends CI_Model
{
    public function construct() 
    {
        parent::__construct();
    }
     /**
     * Backup database
      *
     * @param  bool|string $save_to  Save to file(path & name) or return it as string(when set to FALSE)
     * @param  bool        $compress set to TRUE if you want to compress it to GZip
     * @param  array       $tables   List of tables you want to backup
     * @return string|null
     */
    function backup($save_to = false, $compress = false, $tables = array())
    {
        ini_set('memory_limit', '512M');
        set_time_limit(2000);
        //get all of the tables
        if(count($tables) == 0 ) {
            $tables = array();
            $query = $this->db->query('SHOW TABLES');
            if ($query->num_rows() > 0 ) {
                $field_name = 'Tables_in_' . $this->db->database;
                foreach ( $query->result() as $item )
                {
                    array_push($tables, $item->$field_name);
                }   
            }
        }
        else
        {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }
        //cycle through
        $return = "
        /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
        /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
        /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
        /*!40101 SET NAMES utf8 */;
        /*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
        /*!40103 SET TIME_ZONE='+00:00' */;
        /*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
        /*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
        /*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
        /*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;";
        $return.="\n\n";
        foreach($tables as $table)
        {
            $query = $this->db->get($table);
                $return.= 'DROP TABLE IF EXISTS `' . $table . '`;';
                $query_create = $this->db->query("SHOW CREATE TABLE `$table`");
                $idx = 'Create Table';
                $create_syntax = $query_create->row_array();
                $create_syntax = $create_syntax[$idx];
                $return.= "\n" . $create_syntax . ";\n";
                $return.="LOCK TABLES `". $table ."` WRITE;\n";
                $return.="/*!40000 ALTER TABLE `".$table."` DISABLE KEYS */;\n";
             
            if ($query->num_rows() > 0 ) {

                $con=0;
                foreach ( $query->result() as $row )
                {
                    $values = array();
                    foreach ( $row as $field=>$value )
                    {
                        array_push($values, $this->db->escape($value));
                    }
                    if($con==0) {
                        $return.="INSERT INTO `".$table."` VALUES(" . implode(',', $values) .  ")";
                        $con+=1;
                    }elseif($con<3000) {
                        $return.=",(" . implode(',', $values) .  ")";
                        $con+=1;
                    }else{
                        $return.=",(" . implode(',', $values) .  ");\n";
                        $con=0;
                    }
                }
                $return.=";";
            }
            $return.="\n/*!40000 ALTER TABLE `".$table."` ENABLE KEYS */;\n";
            $return.="UNLOCK TABLES;";
            $return.="\n";

        }
        $return.="
--
-- Dumping routines for database 'biblioteca'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-10-10 17:42:40
DELIMITER $$
/*!50003 
CREATE DEFINER=`root`@`localhost` TRIGGER `detalleprestamo_AFTER_INSERT` AFTER INSERT ON `detalleprestamo` FOR EACH ROW
BEGIN
  
DECLARE cuenta INT(6);
DECLARE id VARCHAR(60);
select cuentausuario into cuenta from prestamos where idprestamo=NEW.prestamo_id;
select idreserva into id from reservas 
where cuentausuario=cuenta and nadqui=NEW.nadqui;
if exists(select * from reservas where cuentausuario=cuenta and nadqui=NEW.nadqui)
then
delete from reservas where idreserva=id;
end if;
END$$
*/;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
DELIMITER $$
/*!50003 CREATE DEFINER=`root`@`localhost` TRIGGER `personas_AFTER_INSERT` AFTER INSERT ON `personas` FOR EACH ROW
BEGIN 
  IF (NEW.curp REGEXP '^[A-Z]{1}[AEIOU]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])[HM]{1}(AS|BC|BS|CC|CS|CH|CL|CM|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]{1}$') =0
    THEN 
    DELETE FROM `personas`
    WHERE curp=NEW.curp;
  END IF;
END$$ */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

" ;
        if ($save_to === false ) { if ($compress === true ) { return gzencode($return); 
        } else { return $return; 
        } 
        }
        else
        {
            $handle  = fopen($save_to, 'w');
            if ($handle !== false ) {
                if ($compress === false ) { fwrite($handle, $return); 
                }
                else { fwrite($handle, gzencode($return)); 
                }
                fclose($handle);
            }
            else
            {
                exit("Error!! Can't write to file with path '$return'");
            }
        }
    }

}
