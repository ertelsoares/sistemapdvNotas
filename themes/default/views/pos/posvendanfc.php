<!doctype html>
    <html>
    <head>
        <meta charset="utf-8">
        <title><?= $page_title . ": " . $inv->id; ?></title>
        <base href="<?= base_url() ?>"/>
        <!--<meta http-equiv="cache-control" content="max-age=0"/>
        <meta http-equiv="cache-control" content="no-cache"/>
        <meta http-equiv="expires" content="0"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <link rel="shortcut icon" href="<?= site_url(); ?>icon.ico"/>
        <link href="<?= $assets ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        -->
       
    </head>
    <body>
    <script>
         setTimeout(() => {
            try {
                window.open('pos/nfe/<?=$inv->id; ?>/1/emitir', 'MyWindow2','toolbar=0,location=0,directories=0,status=0,menubar=yes,scrollbars=yes,resizable=yes,width=600,height=700');
                
                setTimeout(() => {
                    window.location ='<?= site_url('pos'); ?>';
                }, 3000);
        
            } catch (error) {
                console.log(error)
            }
        }, 1000);
     
       
       
    </script>
    <!--
    <style type="text/css" media="all">
    body { color: #000; }
    #wrapper { max-width: 480px; margin: 0 auto; padding-top: 20px; }
    .btn { border-radius: 0; margin-bottom: 5px; }         .table { border-radius: 3px; }
    .table th { background: #f5f5f5; }           .table th, .table td { vertical-align: middle !important; }
    h3 { margin: 5px 0; }
    @media print { .no-print { display: none; }
    #wrapper { max-width: 480px; width: 100%; min-width: 250px; margin: 0 auto; }
    }
    </style>
    <div id="wrapper">
    <div id="receiptData">
    <div class="no-print">
    <span class="col-xs-12 text-center">
    Gerador NFC-e<br><br><br>
    </span>
    <span class="col-xs-12">
    <a class="btn btn-block btn-warning" href=""><?= lang("back_to_pos"); ?></a>
    </span>
    </div>-->