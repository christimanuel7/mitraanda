
<?php
require '../../fungsi.php';
$table = <<<EOT
 ( SELECT * FROM SELECT * FROM tbstokmasuk 
 INNER JOIN tbpemasok ON tbstokmasuk.idPemasok=tbpemasok.idPemasok
 INNER JOIN tbpengguna ON tbstokmasuk.idPengguna=tbpengguna.idPengguna ) viewData EOT;
$primaryKey = 'id_auto';
$columns = array(

	array( 'db' => 'idProduk', 'dt' => 0 ),
	array( 'db' => 'Jenis', 'dt' => 1 ),
	array( 'db' => 'Produk',  'dt' => 2 ),
	array( 'db' => 'Satuan',  'dt' => 3 ),
	array( 'db' => 'stokProduk',  'dt' => 4 ),

);

// SQL server connection information
$sql_details = array(
	'user' => $dbuser,
	'pass' => $dbpass,
	'db'   => $dbname,
	'host' => $dbhost
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( 'ssp.class.php' );
echo json_encode(
	SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);

?>


