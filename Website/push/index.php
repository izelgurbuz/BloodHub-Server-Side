<?php
if($_GET['key'] === "qwerty"){
    require 'oneSignal.php';

    $oneSignal = new oneSignal();

    if($_POST) {

        $text = $_POST['text'];
        $image = $_POST['image'];
        $link = $_POST['link'];
        echo '<pre style="width: 50%;margin-left: 25%">';
        print_r($oneSignal->sendMessage($text, $link,$image));
        echo '</pre>';
        //print_r($oneSignal->sendMessage('Mesaj' , 'http://www.ogzcakar.net'));
        //Gönderilecek Mesaj , Gönderilen Mesaja Tıklandığında Gideceği Adres

    }
    ?>

    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title> Bloodhub Web Services || Web Push Message Sender</title>
        <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async='async'></script>
        <script>
            var OneSignal = window.OneSignal || [];
            OneSignal.push(["init", {
                appId: '<?=$oneSignal->__construct();?>',
                autoRegister: false, /* Set to true to automatically prompt visitors */
                subdomainName: 'bloodhub', // Uygulamayı oluştururken aldığınız subDomain
                notifyButton:
                {
                    enable: true, /* Required to use the notify button */
                    size: 'large', /* One of 'small', 'medium', or 'large' */
                    theme: 'inverse', /* One of 'default' (red-white) or 'inverse" (white-red) */
                    position: 'bottom-left', /* Either 'bottom-left' or 'bottom-right' */
                    offset: {
                        bottom: '0px',
                        left: '0px', /* Only applied if bottom-left */
                        right: '0px' /* Only applied if bottom-right */
                    },
                    prenotify: true, /* Show an icon with 1 unread message for first-time site visitors */
                    showCredit: false, /* Hide the OneSignal logo */
                    text: {
                        'tip.state.unsubscribed': 'Abone ol',
                        'tip.state.subscribed': "Aramıza Hoş Geldin :)",
                        'tip.state.blocked': "Bildirimlerin Engellendi.",
                        'message.prenotify': 'Bildirimlere Abone Olmak İçin Tıklayın.',
                        'message.action.subscribed': "Aramıza Hoş Geldin :)",
                        'message.action.resubscribed': "Abone oldun. Hoşgeldin :)",
                        'message.action.unsubscribed': "Abonelikten Ayrıldın.",
                        'dialog.main.title': 'OneSignal Api',
                        'dialog.main.button.subscribe': 'Abone Ol',
                        'dialog.main.button.unsubscribe': 'Abonelikten Ayrıl',
                        'dialog.blocked.title': 'Engelli Bildirimler.',
                        'dialog.blocked.message': "Follow these instructions to allow notifications:"
                    }
                }
            }]);
        </script>
    </head>
    <body>
    <form style="width: 50%;margin-left: 25%" action="" method ="post" align="center">
        <input style="width: 100%;margin-bottom: 1%" type="text" id="text" name="text" placeholder="Mesaj" /><br>
        <input style="width: 100%;margin-bottom: 1%" type="text" id="link" name="link" placeholder="Mesajın Gideceği Link" /><br>
        <input style="width: 100%;margin-bottom: 1%" type="text" id="image" name="image" placeholder="Mesaj ile gondereceginiz resim" /><br>
        <input type="submit"  />
    </form>

    </body>
    </html>
<?php }else{echo "you are not allowed to use";}?>