<?php
    $ideal = Yii::createComponent('application.modules.sales.extensions.ideal.EIdeal');
?>

<h1>iDEAL Checkout</h1>

<p>Via deze plugin kunt u iDEAL betalingen ontvangen in uw webshop via diverse Payment Service Providers.</p>
<p>Deze plugin is momenteel geconfigureerd om iDEAL transacties te verwerken via <b><?php echo $ideal->gateway_name; ?></b>.<br>
    Meer informatie over deze Payment Service Provider vind u op <a href="<?php echo $ideal->gateway_website; ?>" target="_blank"><?php echo $ideal->gateway_website; ?></a></p>

<?php if($ideal->gateway_validation): ?>
<p>&nbsp;</p>
<h2>Transacties Controleren</h2>
<p>Controleer de status van alle openstaande transacties bij uw Payment Service Provider.</p>
<p><input type="button" value="Controleer openstaande transacties." onclick="javascript: window.open(\'' . GatewayCore::getRootUrl(1) . 'ideal/validate.php\', \'popup\', \'directories=no,height=550,location=no,menubar=no,resizable=yes,scrollbars=yes,status=no,toolbar=no,width=750\');"></p>';

<?php endif; ?>