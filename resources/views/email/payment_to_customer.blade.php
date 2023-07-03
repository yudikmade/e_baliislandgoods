<h1>Hi <?=$first_name?>,</h1>
<p>Thank you for your purchase.</p>
<p>Invoice <b><?=$transaction_code?></b> has been paid, we will begin to process your order right away.</p>
<br>
<p>For more informationed, please click the link below:</p>
<p><a target="_blank" href="<?=route('show_invoice').'/'.$unique_code?>"><?=route('show_invoice').'/'.$unique_code?></a></p>

<br/><br/>
<p>We appreciate your support in our mission,</p>
<br/>
<p>British Colombia Wildlife Federation</p>
