<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
            <title>Invoice For Orders</title>
    </head>
    <body leftmargin="0" marginwidth="0" topmargin="1" marginheight="0" offset="0">
        <center>
            <div style="font-size:12px;background-color:#fdfdfd;margin:0;padding:0;font-family:'Open Sans','Helvetica Neue','Helvetica',Helvetica,Arial,sans-serif;line-height:1.5;height:100%!important;width:100%!important">
                <table bgcolor="#fdfdfd" style="box-sizing:border-box;border-spacing:0;width:100%;background-color:#fdfdfd;border-collapse:separate!important" width="100%">
                    <tbody>
                        <tr>
                            <td style="box-sizing:border-box;padding:0;font-family:'Open Sans','Helvetica Neue','Helvetica',Helvetica,Arial,sans-serif;font-size:12px;vertical-align:top" valign="top">&nbsp;</td>
                            <center>
                                <td style="box-sizing:border-box;padding:0;font-family:'Open Sans','Helvetica Neue','Helvetica',Helvetica,Arial,sans-serif;font-size:12px;vertical-align:top;display:block;width:600px;max-width:600px;margin:0 auto!important" valign="top" width="600">
                                    <div style="box-sizing:border-box;display:block;max-width:600px;margin:0 auto;padding:10px">
                                        <div style="box-sizing:border-box;width:100%;margin-bottom:15px;margin-top:15px">
                                            <table style="box-sizing:border-box;width:100%;border-spacing:0;border-collapse:separate!important" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <td align="left" style="box-sizing:border-box;padding:0;font-family:'Open Sans','Helvetica Neue','Helvetica',Helvetica,Arial,sans-serif;font-size:12px;vertical-align:top;text-align:left" valign="top">
                                                            <span>
                                                                <a href="<?= WEBSITE_PATH ?>" style="box-sizing:border-box;color:#348eda;font-weight:400;text-decoration:none" target="_blank">
                                                                    <img alt="H-MEN" height="22" src="<?= IMAGE_URL_PATH; ?>logo/hlogo01.png" style="max-width:100%;border-style:none;width:150px;height:41px" width="123">
                                                                </a>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div style="box-sizing:border-box;width:100%;margin-bottom:10px;background:#ffffff;border:1px solid #f0f0f0">
                                            <table style="box-sizing:border-box;width:100%;border-spacing:0;border-collapse:separate!important" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <td style="box-sizing:border-box;font-family:'Open Sans','Helvetica Neue','Helvetica',Helvetica,Arial,sans-serif;font-size:12px;vertical-align:top;" valign="top">
                                                            <table width="579" border="0" align="center" cellpadding="0" cellspacing="0" style="border-top: 3px solid #156095;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="padding:20px 20px" bgcolor="#f7f7f7">
                                                                            <table style="font-size:12px!important" width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td align="left" valign="middle"><img src="<?= IMAGE_URL_PATH; ?>icons/ic-invoice.png" width="45" height="45" alt="" class="CToWUd"></td>
                                                                                        <td align="left" valign="middle" style="font-size:12px;padding-left:10px">Order ID<br><span style="color:#156095!important"><strong><?= "#" . $orderData['order_id'] ?></strong></span></td>
                                                                                        <td align="left" valign="middle" style="font-size:12px;padding-left:10px">Order Status<br><span style="color:#156095!important"><strong><?= $orderData['new_status'] ?></strong></span></td>
                                                                                        <td align="left" valign="middle" style="font-size:12px;padding-left:10px">Service<br><span style="color:#156095!important"><strong><?= $orderData['service_name'] ?></strong></span></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="left" style="padding-top:10px;padding-bottom:10px;padding-left:30px;padding-right:30px;font-size:13px!important">
                                                                            <h1 style="font-size:16px!important;margin-bottom:9px!important;color:#333333!important">Dear <?= $orderData['username'] ?>,</h1>
                                                                            Thank you for booking our <?= $orderData['service_name'] ?>.<br>
                                                                                <p style="margin-top:10px!important;margin-bottom:10px!important;float:left!important;clear:both!important">Please use this order id
                                                                                    <span style="color:#156095!important"><?= "#" . $orderData['order_id'] ?></span> 
                                                                                    for further communication.
                                                                                </p>
                                                                                <div style="margin-top:10px;margin-bottom:20px;width:100%;float:left">
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td style="background-color:#f7f7f7;padding:30px">
                                                                                                    <h3 style="font-size:12px!important;margin-bottom:9px!important;color:#156095!important;float:left!important;clear:both!important">Service Lists</h3>
                                                                                                    <div style="width:100%;float:left">
                                                                                                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                                                                            <tbody>
                                                                                                                <?php if (is_array($orderData['services']) && !empty($orderData['services'])) { ?>
                                                                                                                    <?php foreach ($orderData['services'] as $key => $val) { ?>
                                                                                                                        <?php if (is_array($val['services']) && !empty($val['services'])) { ?>
                                                                                                                            <?php foreach ($val['services'] as $k => $v) { ?>
                                                                                                                                <tr>
                                                                                                                                    <td style="padding:10px 0; color: #156095" align="left" valign="top"><?= $k + 1; ?>)
                                                                                                                                        <span style="color:#156095!important; font-size: 12px;"><?php echo (isset($v['quantity']) && $v['quantity'] != '') ? $v['quantity'] . " X " : ''; ?>
                                                                                                                                            <?php echo $v['category_name'] . " " . $v['service_name'] . " " . $v['serviceDescription']; ?> </span>
                                                                                                                                        <br>
                                                                                                                                    </td>
                                                                                                                                    <td colspan="2" style="padding:10px 0;min-width:100px" align="right" valign="top">
                                                                                                                                        <?php if ($v['on_inspection'] != 'Y') { ?>
                                                                                                                                            <span style="color:#156095!important">₹
                                                                                                                                                <strong><?= $v['total_amount']; ?></strong>
                                                                                                                                            </span>
                                                                                                                                        <?php } else { ?>
                                                                                                                                            <span style="color:#156095!important">
                                                                                                                                                <strong><?= "On Inspections" ?></strong>
                                                                                                                                            </span>
                                                                                                                                        <?php } ?>
                                                                                                                                    </td>
                                                                                                                                </tr>
                                                                                                                            <?php } ?>
                                                                                                                        <?php } ?>
                                                                                                                    <?php } ?>
                                                                                                                <?php } ?>
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                    <!--                                                                                <p style="color:#333333!important"> *Inspection amount of Rs 350.00 will be charged upfront. In case you avail the service, the same value will be adjusted against the final bill amount.</p>-->
                                                                                </div>
                                                                                <div style="margin-top:5px;margin-bottom:15px;width:100%;float:left">
                                                                                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td style="padding:10px 0;" align="left" valign="top">
                                                                                                    <span style="font-size:12px!important;line-height:15px!important">
                                                                                                        <strong>Sub Total :</strong>
                                                                                                    </span>
                                                                                                </td>
                                                                                                <td style="padding:10px 0;" align="right" valign="top">
                                                                                                    <span style="color:#333333!important;font-size:12px!important;line-height:15px!important">₹ <?= $orderData['total']['amount']; ?> </span>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td style="padding:10px 0;" align="left" valign="top">
                                                                                                    <span style="font-size:12px!important;line-height:15px!important">
                                                                                                        <strong>GST (<?php echo GST_TAX . "%"; ?>) :</strong>
                                                                                                    </span>
                                                                                                </td>
                                                                                                <td style="padding:10px 0;" align="right" valign="top">
                                                                                                    <span style="color:#333333!important;font-size:12px!important;line-height:15px!important">₹ <?= $orderData['total']['tax']; ?> </span>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php if ($orderData['total']['wallet_amount'] != 0) { ?>
                                                                                                <tr>
                                                                                                    <td style="padding:10px 0;" align="left" valign="top">
                                                                                                        <span style="font-size:12px!important;line-height:15px!important">
                                                                                                            <strong>Wallet Amount :</strong>
                                                                                                        </span>
                                                                                                    </td>
                                                                                                    <td style="padding:10px 0;" align="right" valign="top">
                                                                                                        <span style="color:#333333!important;font-size:12px!important;line-height:15px!important">- ₹ <?= $orderData['total']['wallet_amount']; ?> </span>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            <?php } ?>
                                                                                            <tr>
                                                                                                <td style="padding:15px 0 10px 0; border-top:solid 1px #c6c6c6" align="left" valign="top">
                                                                                                    <span style="color:#333333!important">
                                                                                                        <strong>Total Amount :</strong>
                                                                                                    </span>
                                                                                                </td>
                                                                                                <td style="padding:15px 0 10px 0; border-top:solid 1px #c6c6c6" align="right" valign="top">
                                                                                                    <span style="color:#333333!important">₹
                                                                                                        <strong><?= $orderData['total']['total_amount']; ?> </strong>
                                                                                                    </span>
                                                                                                </td>
                                                                                            </tr> 
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>

                                                                                <div style="margin-top:5px;margin-bottom:20px;width:100%;float:left">
                                                                                    <p style="margin-top:10px!important;margin-bottom:10px!important;float:left!important;clear:both!important">

                                                                                </div>
                                                                                <div style="width:100%; float:left;">
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td width="50%" align="left" valign="top" style="border-right:solid 1px #cccccc;padding-right:20px">
                                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"  style="font-size: 13px !important;">
                                                                                                        <tbody>
                                                                                                            <tr>
                                                                                                                <td style="padding-bottom:7px">
                                                                                                                    <span style="color:#333333!important">Customer Details</span>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td>
                                                                                                                    <span style="width:25px;height:2px;float:left;clear:both;background-color:#fcb43f"></span>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td align="left" valign="top" style="padding-top:15px">
                                                                                                                    <span style="color:#333333!important"><?= $orderData['username']; ?></span><br>
                                                                                                                        Mobile :
                                                                                                                        <span style="color:#333333!important">
                                                                                                                            <a href="tel:<?= '+91' . $orderData['userphone']; ?>" value="<?= '+91' . $orderData['userphone']; ?>" target="_blank"><?= '+91' . $orderData['userphone']; ?></a>
                                                                                                                        </span>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </td>
                                                                                                <td width="50%" align="left" valign="top" style="padding-left:20px">
                                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 13px !important;">
                                                                                                        <tbody>
                                                                                                            <tr>
                                                                                                                <td style="padding-bottom:7px">
                                                                                                                    <span style="color:#333333!important">Company Details</span>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td>
                                                                                                                    <span style="width:25px;height:2px;float:left;clear:both;background-color:#fcb43f"></span>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td align="left" valign="top" style="padding-top:15px">
                                                                                                                    <span style="color:#333333!important">Handee Man</span>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr>
                                                                                                                <td align="left" valign="top" style="">
                                                                                                                    <span style="color:#333333!important">GSTIN: <?= COMPANY_GST_NUMBER; ?></span>
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                                <div style="margin-top:30px;width:100%;float:left">
                                                                                    <strong><b>Terms &amp; Conditions Apply :</b></strong> <br>
                                                                                        <ul style="font-size: 12px;">
                                                                                            <li> For More Details, Click <a href="<?php echo WEBSITE_TOC_PATH; ?>" target="_blank"><strong>here</strong> </a></li>
                                                                                        </ul>
                                                                                </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr><td bgcolor="#f7f7f7" style="border:solid 1px #f7f7f7;border-top:none;padding-top:10px;padding-bottom:10px">
                                                            <table width="550" border="0" cellspacing="0" cellpadding="0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td width="150">
                                                                            <table style="border-right:solid 1px #156095" width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="150" align="center" valign="middle">
                                                                                            <img src="<?php echo IMAGE_URL_PATH . 'icons/guarantee/ic-step1.png'; ?>" width="30" alt="" class="CToWUd">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding-top:10px;color:#156095!important;font-size:10px!important;line-height:13px!important" align="center">Verified
                                                                                            <br>
                                                                                                Professionals
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                        <td width="150">
                                                                            <table style="border-right:solid 1px #156095" width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="150" align="center" valign="middle">
                                                                                            <img src="<?php echo IMAGE_URL_PATH . 'icons/guarantee/ic-step2.png'; ?>" width="30" alt="" class="CToWUd">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding-top:10px;color:#156095!important;font-size:10px!important;line-height:13px!important" align="center">Insured
                                                                                            <br>
                                                                                                Work
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                        <td width="150">
                                                                            <table style="border-right:solid 1px #156095" width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="150" align="center" valign="middle">
                                                                                            <img src="<?php echo IMAGE_URL_PATH . 'icons/guarantee/ic-step3.png'; ?>" width="30" alt="" class="CToWUd">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding-top:10px;color:#156095!important;font-size:10px!important;line-height:13px!important" align="center">Satisfaction
                                                                                            <br>
                                                                                                Guaranteed
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                        <td width="150">
                                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td width="150" align="center" valign="middle">
                                                                                            <img src="<?php echo IMAGE_URL_PATH . 'icons/guarantee/ic-step4.png'; ?>" width="30" alt="" class="CToWUd">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding-top:10px;color:#156095!important;font-size:10px!important;line-height:13px!important" align="center">Easy
                                                                                            <br>
                                                                                                Payment
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <center>
                                            <div style="box-sizing:border-box;clear:both;width:100%">
                                                <table style="box-sizing:border-box;width:100%;border-spacing:0;font-size:12px;border-collapse:separate!important" width="100%">
                                                    <tbody>
                                                        <tr style="font-size:12px">
                                                            <td align="center" style="box-sizing:border-box;font-family:'Open Sans','Helvetica Neue','Helvetica',Helvetica,Arial,sans-serif;vertical-align:top;font-size:12px;text-align:center;padding:20px 0" valign="top"><span style="float:none;display:block;text-align:center"><a href="#" style="box-sizing:border-box;color:#348eda;font-weight:400;text-decoration:none;font-size:12px" target="_blank" ><img alt="H-MEN" height="16" src="<?= IMAGE_URL_PATH; ?>logo/hlogo01.png" style="max-width:100%;border-style:none;font-size:12px;width:125px;height:34px" width="89" ></a></span>
                                                                <p style="margin:0;color:#294661;font-family:'Open Sans','Helvetica Neue','Helvetica',Helvetica,Arial,sans-serif;font-weight:300;font-size:12px;margin-bottom:5px">&copy; <span >H-MEN</span>, <?= EMAIL_FOOTER_TEXT; ?></p>
                                                                <p style="margin:0;color:#294661;font-family:'Open Sans','Helvetica Neue','Helvetica',Helvetica,Arial,sans-serif;font-weight:300;font-size:12px;margin-bottom:5px"><a href="<?= SOCIAL_MEDIA_LINK_FB ?>" style="box-sizing:border-box;color:#348eda;font-weight:400;text-decoration:none;font-size:12px;padding:0 5px" target="_blank" >Facebook</a> <a href="<?= SOCIAL_MEDIA_LINK_TW ?>" style="box-sizing:border-box;color:#348eda;font-weight:400;text-decoration:none;font-size:12px;padding:0 5px" target="_blank" >Twitter</a> <a href="<?= SOCIAL_MEDIA_LINK_IN ?>" style="box-sizing:border-box;color:#348eda;font-weight:400;text-decoration:none;font-size:12px;padding:0 5px" target="_blank" >Instagram</a></p>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </center>
                                    </div>
                                </td>
                            </center>
                        </tr>
                    </tbody>
                </table>
            </div>
        </center>
    </body>
</html>