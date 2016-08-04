<?php
    class Database
    {
        private $host,$user,$db,$port;
        
        public function __construct()
        {
            $this->host = "0.0.0.0";
            $this->user = "clajps0n";
            $this->db = "infobip_sms";
            $this->port = "3306";
        }
        
        function guardar($to,$from,$message,$hour)
        {
            $conn = mysql_connect($host,$user,$pw);
            
            if($conn)
            {
                error_log('conexion satisfactoria!');
            }else
            {
                error_log('conexion no lograda!');
            }
            
            mysql_select_db($db);
            
            $sql = "INSERT INTO `infobip_sms`.`mensajes` (`id`, `to`, `from`, `message`, `date`) VALUES (NULL, '".$to."', '".$from."', '".$hour."');";
            $res = mysql_query($sql,$conn);
            
            if($res)
            {
                error_log('insercion exitosa.');
                
            }
            
            mysql_close($conn);
        }
    }


    
?>