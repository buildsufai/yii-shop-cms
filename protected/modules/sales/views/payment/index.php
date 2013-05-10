<h1>Bedankt voor uw bestelling</h1>

<?php if($model->shipping_methode == Order::SHIPPING_PICKUP): ?>

<p>Uw heeft ervoor gekozen om de bestelling bij ons op te halen,
U kunt uw bestelling betalen zodra u het bij ons ophaald.<p>

<p>U ontvangt van ons een email zodra uw bestelling klaar ligt</p>

<?php elseif($model->shipping_methode == Order::SHIPPING_POSTAL): ?>

    <?php if($model->status == Order::STATUS_PENDING): // nog niet betaald ?>

    <p>U heeft ervoor gekozen om de bestelling te laten verzenden<br />
    Uw bestelling zal door ons worden verzonden zodra wij uw betalling ontvangen hebben<br />
    Wij bieden u de volgende opties om de betaling te voldoen:</p>

    <h2>Betaal makkelijk en snel met iDeal</h2>
    Te betalen bedrag: <b><?php echo $model->totalPriceText; ?></b><br>
    Uw order nummer: <b><?php echo $model->id; ?></b>
    <p>
    <?php echo $content; ?>
    </p>
    <h2>Bank transactie</h2>

    <p>Als u niet via iDeal wilt betalen kunt u ook het aankoop bedrag overmaken naar onze rekening.<br />
        Houd er rekening mee dat het iets langer duurt om uw betaling te verwerken. (+/- 5 werkdagen)</p>
    <p>Zodra wij de volledige betaling ontvangen hebben zullen wij u hiervan attenderen.
    Ook attenderen wij u zodra de bestelling verzonden is.</p>

    <p>Maak het aankoop bedrag van <?php echo $model->totalPriceText; ?> over naar het volgende rekeningnummer:</p>

    <strong>Rekening nr:</strong> <?php echo Yii::app()->params['bank_nr']; ?> (Rabobank)<br />
    <strong>Ter attentie van:</strong> <?php echo Yii::app()->webshop->name; ?>

    <p>
    <strong>LET OP:</strong> Vergeet niet het volgende order nummer erbij te vermelden. Order Nr: <strong>#<?php echo $model->id; ?></strong>
    </p>

    <?php else: // bestelling word al verwerkt ?>

    <p>Wij hebben de betaling van uw bestelling ontvangen en deze zal zo spoedig mogelijk naar u verzonden worden.<br />
        Wij houden u op de hoogte!</p>

    <?php endif; ?>

<?php endif; ?>
    <br>