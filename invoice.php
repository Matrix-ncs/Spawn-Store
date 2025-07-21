<?php
require_once('tcpdf/tcpdf.php');

if (isset($_POST['game_name'], $_POST['game_price'], $_POST['purchase_time'], $_POST['username'])) {
    $gameName = $_POST['game_name'];
    $gamePrice = $_POST['game_price'];
    $purchaseTime = $_POST['purchase_time'];
    $username = $_POST['username'];


    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator('Spawn Store');
    $pdf->SetAuthor('Spawn Store');
    $pdf->SetTitle('Invoice for ' . $gameName);
    $pdf->SetMargins(15, 20, 15);
    $pdf->SetAutoPageBreak(TRUE, 20);
    $pdf->AddPage();


    $logoPath = 'img/spawn store logo.png'; 
    if (file_exists($logoPath)) {
        $pdf->Image($logoPath, 95, 35, 20); 
    }

    $pdf->Ln(45); 


    $htmlTitle = '
    <div style="text-align: center;">
        <span style="color: orange; font-family: Apple Chancery, cursive; font-size: 40px; font-weight: bold;">Spawn</span>
        <span style="color: red; font-family: Apple Chancery, cursive; font-size: 40px; font-weight: bold;"> Store</span>
    </div>
    ';
    $pdf->writeHTML($htmlTitle, true, false, true, false, '');

    $pdf->Ln(5);


    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->SetTextColor(0, 102, 204); 
    $pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');

    $pdf->Ln(10);


    $pdf->SetFont('helvetica', '', 12);
    $pdf->SetTextColor(0, 0, 0); 
    $pdf->writeHTML('<b>Customer Name:</b> ' . htmlspecialchars($username), true, false, true, false, '');

    $pdf->Ln(5);


    $htmlTable = '
    <table cellpadding="8" cellspacing="0" border="1" style="border-color:rgb(255, 0, 0);">
        <thead style="background-color: #053246; color: #ffffff;">
            <tr style="text-align:center;">
                <th><b>Game Name</b></th>
                <th><b>Price</b></th>
                <th><b>Purchase Date</b></th>
            </tr>
        </thead>
        <tbody>
            <tr style="background-color: #e6f2ff;text-align:center;">
                <td>' . htmlspecialchars($gameName) . '</td>
                <td>$' . htmlspecialchars($gamePrice) . '</td>
                <td>' . htmlspecialchars($purchaseTime) . '</td>
            </tr>
        </tbody>
    </table>
    ';
    $pdf->writeHTML($htmlTable, true, false, false, false, '');

    $pdf->Ln(10);


    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetTextColor(5, 50, 70);
    $thankYouNote = '
    <div style="text-align: center; font-size: 13px;">
        Thank you for shopping at 
        <span style="color: orange; font-family: Apple Chancery, cursive; font-weight: bold;font-size: 15px;">Spawn</span>
        <span style="color: red; font-family: Apple Chancery, cursive; font-weight: bold;font-size: 15px;">Store</span>!
        <br>We hope you enjoy your new game!
    </div>
    ';
    $pdf->writeHTML($thankYouNote, true, false, true, false, '');

        
        $pageHeight = $pdf->getPageHeight();
        $footerY = $pageHeight - 20; 

        
        $pdf->Line(15, $footerY, 195, $footerY); 

       
        $pdf->Line(15, $footerY + 5, 195, $footerY + 5); 


    $pdf->Output('invoice.pdf', 'D');
} else {
    echo "Invalid invoice request.";
}
?>

