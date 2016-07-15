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
$EXTRACTED_PAPER_DATA = array();
$EXTRACTED_SUMMARY_DATA = array();

function regexForm($s){
    $s = str_replace("/", "\/", $s);
    $s = str_replace(".","\.", $s);
    $s = str_replace("(", "\(", $s);
    $s = str_replace(")","\)", $s);
    $s = str_replace("[", "\[", $s);
    $s = str_replace("]","\]", $s);
    return $s;
}

//GET USER URL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://scholar.google.co.id/citations?user=U1ZWDAgAAAAJ&hl=en&oi=ao&pagesize=1000');
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
$data = curl_exec($ch);

//GET PAPER DETAIL
$left = regexForm('<table id="gsc_a_t">');
$right = regexForm('</table>');
$regex = "/$left(.*?)$right/s";
$matches = array();
preg_match_all($regex, $data, $matches);

$tableData = $matches[0][0];

    //get the row
$left= regexForm('<tr class="gsc_a_tr">');
$right = regexForm('</tr>');
$regex = "/$left(.*?)$right/s";
$matches = array();
preg_match_all($regex, $tableData, $matches);
$tableRows = $matches[0];

foreach($tableRows as $row){

    //mengambil citedby, tahun
    $left=regexForm('<td');
    $right= regexForm('</td>');
    $regex = "/$left(.*?)$right/s";
    $matches = array();
    preg_match_all($regex, $row, $matches);
    $detail = array('makalah' => $matches[0][0], 'cited' => str_replace("*","",$matches[0][1]), 'year' => $matches[0][2]);
    $EXTRACTED_PAPER_DATA[] = $detail;
}

//GET USER SUMMARY DETAIL
    //get table
$left= regexForm('<table id="gsc_rsb_st">');
$right = regexForm('</table>');
$regex = "/$left(.*?)$right/s";
$matches = array();
preg_match_all($regex, $data, $matches);
$tableSummary = $matches[0][0];
    //get row
$left= regexForm('<tr>');
$right = regexForm('</tr>');
$regex = "/$left(.*?)$right/s";
$matches = array();
preg_match_all($regex, $tableSummary, $matches);
$rowCitation = $matches[0][1];
$rowHindex = $matches[0][2];

    //get citation & hindex
$getDetail = $rowCitation . $rowHindex;
$left=regexForm('<td class="gsc_rsb_std">');
$right= regexForm('</td>');
$regex = "/$left(.*?)$right/s";
$matches = array();
preg_match_all($regex, $getDetail, $matches);
$userDetail = array('citation' => $matches[0][0], 'hindex' => $matches[0][2]);
$EXTRACTED_SUMMARY_DATA = $userDetail;

    //get nama
$left= regexForm('<div id="gsc_prf_in">');
$right = regexForm('</div>');
$regex = "/$left(.*?)$right/s";
$matches = array();
preg_match_all($regex, $data, $matches);

$userNama = $matches[1][0];

$totalPaper = sizeof($EXTRACTED_PAPER_DATA);

//PRINT
echo "Nama : " . $userNama . "<br>";
echo "Citation : " . $EXTRACTED_SUMMARY_DATA[citation] . "<br>";
echo "H-Index : " . $EXTRACTED_SUMMARY_DATA[hindex] . "<br>";
echo "Total Paper : " . $totalPaper . "<br><br>";

for($i=0; $i<$totalPaper; $i++){
    echo "Paper : " . $EXTRACTED_PAPER_DATA[$i][makalah];
    echo "Citedby : " . $EXTRACTED_PAPER_DATA[$i][cited] . "<br>";
    echo "Year : " . $EXTRACTED_PAPER_DATA[$i][year] . "<br><br>";
}

?>
</body>
</html>
