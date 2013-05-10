<h1>Bedankt voor uw bestelling</h1>

<?php if($model->shipping_methode == Order::SHIPPING_PICKUP): ?>

<p>Uw heeft ervoor gekozen om de bestelling bij ons op te halen,
U kunt uw bestelling betalen zodra u het bij ons ophaald.<p>

<p>U ontvangt van ons een email zodra uw bestelling klaar ligt</p>

<?php elseif($model->shipping_methode == Order::SHIPPING_POSTAL): ?>

<p>Uw bestelling zal door ons worden verzonden zodra wij uw betalling ontvangen hebben</p>

<h2>Betaal makkelijk en snel met iDeal</h2>

<form action="<?php echo $this->createUrl('/sales/payment/transaction/', array('order_id'=>$model->id)); ?>" method="post" id="checkout">
<p>
    <b>Kies uw bank</b><br>
    <select name="issuer_id" style="margin: 6px; width: 200px;">
        <script src="http://www.targetpay.com/ideal/issuers-nl.js"></script>
    </select><br>
    <input type="submit" value="Verder"></p>
</form>


<h2>Bank transactie</h2>

<p>Als u niet via iDeal wilt betalen kunt u ook het aankoop bedrag overmaken naar onze rekening.
    Houd er rekening mee dat het iets langer duurd om uw betaling te verwerken. (+/- 5 werkdagen)</p>
<p>Zodra wij de volledige betaling ontvangen hebben zullen wij u hiervan attenderen.
Ook attenderen wij u zodra de bestelling verzonden is.</p>

<p>Maak het aankoop bedrag van <?php echo $model->totalPrice; ?> over naar het volgende rekeningnummer:</p>

<strong>Rekening nr:</strong> <?php echo Yii::app()->params['bank_nr']; ?> (Rabobank)<br />
<strong>ter attentie van:</strong> <?php echo Yii::app()->webshop->name; ?>

<p>
<strong>LET OP:</strong> Vergeet niet het volgende order nummer bij de transactie te vermelden.<br />
<strong>ORDER NR: <?php echo $model->id; ?></strong>
</p>

<?php endif; ?>
