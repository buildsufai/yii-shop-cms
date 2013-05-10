<div style="margin: 0px; font-family: Arial; font-size: 11px; background-color: #f9d5a3;">
<table cellspacing="0" cellpadding="0" border="0" style="background-color: #661111; width: 100%; min-height: 96px;">
    <tbody>
        <tr>
            <td style="text-align: left; width: 280px; padding-left: 20px; vertical-align: top;">
                <a target="_blank" href="<?php echo Yii::app()->request->hostInfo; ?>" title=" Klik om naar de website te gaan " style="text-decoration: none;">
                    <img title="<?php echo Yii::app()->administration->name; ?>" style="border: 0px none;" src="<?php echo Yii::app()->request->hostInfo . Yii::app()->theme->baseUrl . '/images/logo.png'; ?>" />
                </a>
            </td>
        </tr>
    </tbody>
</table>

    <?php echo $content; ?>

<div style="background-color: #661111; text-align: left; margin: 12px 0; color: rgb(153, 153, 153);border-top: 1px solid rgb(153, 153, 153); padding-left: 20px;">
<table cellspacing="0" cellpadding="0" border="0" style="font-size: 12px; width: 100%; min-height: 96px; background-color: #661111; margin: 14px 0; width: auto; font-family: Arial; color: rgb(255, 255, 255);">
                <tbody>
                    <tr>
                        <td style="padding-right: 5px; white-space: nowrap;">
                        <strong><?php echo Yii::app()->administration->name; ?></strong><br>
                        <?php echo Yii::app()->administration->address; ?><br>
                        <?php echo Yii::app()->administration->postalcode . " " . Yii::app()->administration->place; ?><br>
                        </td>
                        <td style="width: 75px;">&nbsp;</td>
                        <td style="white-space: nowrap;"><strong>Tel:</strong>&nbsp;<br>
                        <strong>Email:</strong>&nbsp;<br>
                        <strong>Web:</strong>&nbsp;</td>
                        <td style="text-align: right; padding-right: 5px; white-space: nowrap;">
                        <?php echo Yii::app()->administration->phone_nb; ?><br>
                        <a style="color:#fff;" href="mailto:<?php echo Yii::app()->administration->email; ?>" target="_blank" title="Klik om ons een mail te sturen" style="text-decoration: none;"><?php echo Yii::app()->administration->email; ?></a><br>
                        <a style="color:#fff;" href="<?php echo Yii::app()->request->hostInfo; ?>" target="_blank" title="Klik om naar de website te gaan" style="text-decoration: none;"><?php echo Yii::app()->request->hostInfo; ?></a> </td>
                        <td style="width: 75px;">&nbsp;</td>
                        <td style="white-space: nowrap;">
                        <strong>Rabo Bank:</strong>&nbsp;<br>
                        <strong>KVK:</strong>&nbsp;<br>
                        <strong>BTW:</strong>&nbsp;</td>
                        <td style="text-align: right; white-space: nowrap;">
                        <?php echo Yii::app()->params['bank_nr']; ?><br>
                        <?php echo Yii::app()->params['kvk_nr']; ?><br>
                        <?php echo Yii::app()->params['btw_nr']; ?></td>
                    </tr>
                </tbody>
            </table>
</div>
</div>
<br>