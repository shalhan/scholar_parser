<html>
    <head>
        <link rel="stylesheet" type="text/css" href="styles.css">

    </head>
    <body>
<?php
// A simple web site in Cloud9 that runs through Apache
// Press the 'Run' button on the top to start the web server,
// then click the URL that is emitted to the Output tab of the console

// get host name from URL
$EXTRACTED_DATA = array();
function regexForm($s){
    $s = str_replace("/", "\/", $s);
    $s = str_replace(".","\.", $s);
    $s = str_replace("(", "\(", $s);
    $s = str_replace(")","\)", $s);
    $s = str_replace("[", "\[", $s);
    $s = str_replace("]","\]", $s);
    return $s;
}

//get user url
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://scholar.google.co.id/citations?user=4KgDYWkAAAAJ&hl=id&oi=ao&pagesize=100');
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$data = curl_exec($ch);

$left = regexForm('<table id="gsc_a_t">');
$right = regexForm('</table>');
$regex = "/$left(.*?)$right/s";
$matches = array();
preg_match_all($regex, $data, $matches);

$tableData = $matches[0][0];
//print_r($tableData);


//get the row
$left= regexForm('<tr class="gsc_a_tr">');
$right = regexForm('</tr>');
$regex = "/$left(.*?)$right/s";
$matches = array();
preg_match_all($regex, $tableData, $matches);
$tableRows = $matches[0];

//print_r($matches);

foreach($tableRows as $row){

    //mengambil citedby, tahun
    $left=regexForm('<td');
    $right= regexForm('</td>');
    $regex = "/$left(.*?)$right/s";
    $matches = array();
    preg_match_all($regex, $row, $matches);
    //print_r($matches[0]);
    $detail = array('makalah' => $matches[0][0], 'cited' => $matches[0][1], 'year' => $matches[0][2]);
    $EXTRACTED_DATA[] = $detail;
}

print_r($EXTRACTED_DATA);

?>
</body>
</html>
