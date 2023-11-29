
<body style="position: relative;width:400px; height: 100%; margin: 0 auto !important; color: #001028;background: #FFFFFF; font-size: 12px; border: 1px solid #efefef;">
    <table width="100%" cellspacing="0" align="center" cellpadding="0">
        <tbody>
            <tr>
                <td style="width:100%;vertical-align:middle;" align="center">
                    <img src="<?= APPPATH ?>'/public/assets/img/bdc_logo.png" alt="" style="width:200px;">
                </td>
            </tr>
        </tbody>
    </table>
    <br><br style="line-height:30px;">
    <table style="background-color:#EFEFEF;" width="100%" cellspacing="0" cellpadding="">
        <tbody>
            <tr>
                <td style="font-size:18px;" align="center"><b>INVOICE #<?= $data['var_Invoice_id']?></b></td>
            </tr>
            <tr>
                <td style="font-size:10px;" align="center">Order Date: <?= date('M d, Y', strtotime($data['var_invoice_date'])) ?> </td>
            </tr>
        </tbody>
    </table>
    <br><br style="line-height:30px;">
    <table style="border:1px solid #e1e1e1" width="100%" cellspacing="0" cellpadding="5">
        <tbody>
            <tr style="background-color:#EFEFEF;">
                <td width="40%">
                    <table width="100%" cellspacing="0" cellpadding="4">
                        <tbody>
                            <tr>
                                <td style="font-size:10px;"><b>Bill To :</b></td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;"><b>Name: </b><?= $data['companyAddress']['var_name'] ?></td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;"><b>Address: </b><?= $data['companyAddress']['var_address'] ?>
                                <?php 
                                if($data['companyAddress']['cityName']!=""){
                                    echo ', '.$data['companyAddress']['cityName'];
                                    
                                }
                                if($data['companyAddress']['stateName']!=""){
                                    echo ', '.$data['companyAddress']['stateName'];
                                    
                                }
                                if($data['companyAddress']['countryName']!=""){
                                    echo ', '.$data['companyAddress']['countryName'];
                                    
                                }
                                if($data['companyAddress']['var_pincode']!=""){
                                    echo ', '.$data['companyAddress']['var_pincode'];
                                    
                                } ?>
                                </td>
                            </tr>
                            <?php if($data['companyAddress']['var_gst']!=""){ ?>
                                <tr>
                                    <td style="font-size:10px;"><b>GST No.: </b><?= strtoupper($data['companyAddress']['var_gst']) ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </td>
                <td width="10%" style="border-right: 1px solid #e1e1e1;"></td>
                <td width="50%">
                    <table width="100%" cellspacing="0" cellpadding="4">
                        <tbody>
                            <tr>
                                <td style="font-size:10px;" align="left"><b>Ship To:</b></td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;" align="left"><b>Name: </b><?= $data['customerAddress']['var_displayname'] ?></td>
                            </tr>
                            <tr>
                                <td style="font-size:10px;"><b>Address: </b><?= $data['customerAddress']['var_office_address'] ?>
                                <?php 
                                if($data['customerAddress']['cityName']!=""){
                                    echo ', '.$data['customerAddress']['cityName'];
                                    
                                }
                                if($data['customerAddress']['stateName']!=""){
                                    echo ', '.$data['customerAddress']['stateName'];
                                    
                                }
                                if($data['customerAddress']['countryName']!=""){
                                    echo ', '.$data['customerAddress']['countryName'];
                                    
                                }
                                if($data['customerAddress']['var_pincode']!=""){
                                    echo ', '.$data['customerAddress']['var_pincode'];
                                    
                                } ?>
                                </td>
                            </tr>
                            <?php if($data['customerAddress']['var_gst']!=""){ ?>
                                <tr>
                                    <td style="font-size:10px;"><b>GST No.: </b><?= strtoupper($data['customerAddress']['var_gst']) ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <br><br style="line-height:15px;">
    <table style="border:1px solid #e1e1e1" width="100%" cellspacing="0" cellpadding="5">
        <tbody>
            <tr style="background-color:#EFEFEF;">
                <td style="width:3%;font-size:10px;text-align:center;border:1px solid #e1e1e1;"><b>Sr. No.</b></td>
                <td style="width:37%;font-size:10px;text-align:center;border:1px solid #e1e1e1;"><b>Item Name</b></td>
                <td style="width:10%;font-size:10px;text-align:center;border:1px solid #e1e1e1;"><b>HSN</b></td>
                <td style="width:10%;font-size:10px;text-align:center;border:1px solid #e1e1e1;"><b>Price</b></td>
                <td style="width:10%;font-size:10px;text-align:center;border:1px solid #e1e1e1;"><b>Quantity</b></td>
                <td style="width:10%;font-size:10px;text-align:center;border:1px solid #e1e1e1;"><b>TAX</b></td>
                <td style="width:20%;font-size:10px;text-align:center;border:1px solid #e1e1e1;"><b>Total</b></td>
            </tr>
            <?php if(!empty($data['invoice_item'])){
                $count = 1;
                foreach($data['invoice_item']  as $val){ ?>
                    <tr style="background-color:#EFEFEF;">
                        <td style="width:3%;font-size:10px;text-align:center;border:1px solid #e1e1e1;"><?= $count++ ?></td>
                        <td style="width:37%;font-size:10px;text-align:center;border:1px solid #e1e1e1;"><?= $val['var_item_name'] ?></td>
                        <td style="width:10%;font-size:10px;text-align:center;border:1px solid #e1e1e1;"><?= $val['var_hsn'] ?></td>
                        <td style="width:10%;font-size:10px;text-align:center;border:1px solid #e1e1e1;"><?= '<span style="font-family:dejavusans;">&#8377;</span>'.$val['var_rate'] ?></td>
                        <td style="width:10%;font-size:10px;text-align:center;border:1px solid #e1e1e1;"><?= $val['var_qty'] ?></td>
                        <td style="width:10%;font-size:10px;text-align:center;border:1px solid #e1e1e1;"><?= ($val['var_percent']>0)?$val['var_percent'].'%':'' ?></td>
                        <td style="width:20%;font-size:10px;text-align:center;border:1px solid #e1e1e1;"><?= '<span style="font-family:dejavusans;">&#8377;</span>'.$val['var_amount'] ?></td>
                    </tr>
                <?php }
            } ?> 
        </tbody>
    </table>
    <table style="border:1px solid #e1e1e1" width="100%" cellspacing="0" cellpadding="5">
        <tbody>
            <tr style="background-color:#EFEFEF;">
                <td style="width:80%;font-size:10px;text-align:right;border:1px solid #e1e1e1;"><b>Sub total</b></td>
                <td style="width:20%;font-size:9px;text-align:center;border:1px solid #e1e1e1;"><?= '<span style="font-family:dejavusans;">&#8377;</span>'.$data['var_subtotal'] ?></td>
            </tr>
            <?php if($data['var_gst']> 0){
                if($data['companyAddress']['fk_state']!=$data['customerAddress']['fk_state']){ ?>
                    <tr style="background-color:#EFEFEF;">
                        <td style="width:80%;font-size:10px;text-align:right;border:1px solid #e1e1e1;"><b>IGST</b></td>
                        <td style="width:20%;font-size:9px;text-align:center;border:1px solid #e1e1e1;"><?= '<span style="font-family:dejavusans;">&#8377;</span>'.$data['var_gst'] ?></td>
                    </tr>
                <?php }else{ ?>
                    <tr style="background-color:#EFEFEF;">
                        <td style="width:80%;font-size:10px;text-align:right;border:1px solid #e1e1e1;"><b>SGST</b></td>
                        <td style="width:20%;font-size:9px;text-align:center;border:1px solid #e1e1e1;"><?= '<span style="font-family:dejavusans;">&#8377;</span>'.$data['var_gst']/2 ?></td>
                    </tr>
                    <tr style="background-color:#EFEFEF;">
                        <td style="width:80%;font-size:10px;text-align:right;border:1px solid #e1e1e1;"><b>CGST</b></td>
                        <td style="width:20%;font-size:9px;text-align:center;border:1px solid #e1e1e1;"><?= '<span style="font-family:dejavusans;">&#8377;</span>'.$data['var_gst']/2 ?></td>
                    </tr>
                <?php }
            }
            if($data['var_adjustment'] > 0){ ?>
                <tr style="background-color:#EFEFEF;">
                    <td style="width:80%;font-size:10px;text-align:right;border:1px solid #e1e1e1;"><b>Adjustment</b></td>
                    <td style="width:20%;font-size:9px;text-align:center;border:1px solid #e1e1e1;"><?= '<span style="font-family:dejavusans;">&#8377;</span>'.$data['var_adjustment'] ?></td>
                </tr>
            <?php } ?>
            <tr style="background-color:#EFEFEF;">
                <td style="width:80%;font-size:10px;text-align:right;border:1px solid #e1e1e1;"><b>Total Amount</b></td>
                <td style="width:20%;font-size:9px;text-align:center;border:1px solid #e1e1e1;"><?= '<span style="font-family:dejavusans;">&#8377;</span>'.$data['var_final_amount'] ?></td>
            </tr>
        </tbody>
    </table>
</body>
