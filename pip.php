<?php
$authhost="{vps.clustercwp.ml:110/pop3/notls}";
$user="info@cwp-billing.studio4host.com";
$pass="123qaz//**";
if ($connection=imap_open( $authhost, $user, $pass ))
{
    echo "<h1>Connected</h1>\n";
    $num_msg = imap_num_msg($connection);
    echo ">>> {$num_msg}\n";
    for($i = 1; $i <= $num_msg; $i++) {
        $inbox[] = array(
            'index'     => $i,
            'header'    => imap_headerinfo($connection, $i),
            'body'      => imap_body($connection, $i),
            'structure' => imap_fetchstructure($connection, $i)
        );
    }
    print_r($inbox);
    imap_close($connection);
} else
{
    echo "<h1>FAIL!</h1>\n";
}

?>

