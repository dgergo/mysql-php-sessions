<?php
/*
Include this file in your project's header to initiate and manage the users' session data via a mysql database.
*/

// Include the main file which contains a simple database connection wrapper.
include('database.php');

// Initiate the database connection.


if(!class_exists('dgSessionHandler')) {
    class dgSessionHandler implements SessionHandlerInterface {
        
        public function __construct() {
            $connection = dg_initiate_db_connection();
            // Instantiate new Database object
            $this->db = $connection;
        
            // Set handler to overide SESSION
            session_set_save_handler(
                array($this, "open"),
                array($this, "close"),
                array($this, "read"),
                array($this, "write"),
                array($this, "destroy"),
                array($this, "gc")
            );
        }

        /**
         * Open
         */
        #[\ReturnTypeWillChange]
        public function open($savepath, $id) {
            // If successful
            $open = $this->db->query("SELECT id FROM dg_php_sessions WHERE id='$id' LIMIT 1");
            if($open) {
                // Return True
                return true;
            } 
            // Return False
            return false;
        }
        /**
         * Read
         */
        #[\ReturnTypeWillChange]
        public function read($id) {
            // Set query
            $read = $this->db->query("SELECT `data` FROM dg_php_sessions WHERE id='$id' LIMIT 1");
            if ($read && $read->num_rows > 0) {
                while($readRow = $read->fetch_assoc()) {
                    return $readRow['data'];
                }
            } else {
                return '';
            }
        }

        /**
         * Write
         */
        #[\ReturnTypeWillChange]
        public function write($id, $data) {
            // Create time stamp
            $access = time();

            // Set query
            if ($this->db->query("REPLACE INTO dg_php_sessions (id,access,`data`) VALUES ('$id', '$access', '$data')")) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Destroy
         */
        #[\ReturnTypeWillChange]
        public function destroy($id) {
            // Set query
            if ($this->db->query("DELETE FROM dg_php_sessions WHERE id='$id' LIMIT 1")) {
                return true;
            } else {

                return false;
            }
        }
        /**
         * Close
         */
        #[\ReturnTypeWillChange]
        public function close() {
            // Close the database connection
            return true; // I don't think we need to close it
            if($this->db->close){
                // Return True
                return true;
            }
            // Return False
            return false;
        }

        /**
         * Garbage Collection
         */
        #[\ReturnTypeWillChange]
        public function gc($max) {
            // Calculate what is to be deemed old
            $old = time() - $max;

            if ($this->db->query("DELETE FROM dg_php_sessions WHERE access<'$old'")) {
                return true;
            } else {
                return false;
            }
        }

        public function __destruct()
        {
            $this->close();
        }

    }
}

// If the session has not been started yet, initiate it now.
if (session_status() == PHP_SESSION_NONE) {
    new dgSessionHandler();
    session_start();
}