<?php
class YabanciKimlikNoDogrula {
    private $kimlikNo;
    private $ad;
    private $soyad;
    private $dogumGun;
    private $dogumAy;
    private $dogumYil;
    

    public static function kimlikNo($kimlikNo) {
        $instance = new static;
        if(strlen($kimlikNo) !=11)
            throw new \Exception('Yabanci Kimlik No 11 hane olmalıdır');
        $instance->kimlikNo = $kimlikNo;
        return $instance;
    }

    public function ad($ad) {
        $this->ad = $this->upperCase($ad);
        return $this;
    }

    public function soyad($soyad) {
        $this->soyad = $this->upperCase($soyad);
        return $this;
    }

    public function dogumGun($dogumGun) {
        $this->dogumGun = $dogumGun;
        return $this;
    }

    public function dogumAy($dogumAy) {
        $this->dogumAy = $dogumAy;
        return $this;
    }

    public function dogumYil($dogumYil) {
        $this->dogumYil = $dogumYil;
        return $this;
    }

    private function upperCase($string) {
        return mb_convert_case($string, MB_CASE_UPPER, "UTF-8");
    }

    public function sorgula() {
        if (!isset($this->ad) || !isset($this->soyad) || !isset($this->dogumGun) || !isset($this->dogumAy) || !isset($this->dogumYil) || !isset($this->kimlikNo )) {
            throw new \Exception("Doğrulama için Yabanci Kimlik No, Ad, Soyad, Doğum Günü, Ayı, Yılı tanımlanmış olması gerekir");
        }
        $toSend =
            '<?xml version="1.0" encoding="utf-8"?>
                <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                  <soap12:Body>
                        <YabanciKimlikNoDogrula xmlns="http://tckimlik.nvi.gov.tr/WS">
                            <KimlikNo>' . $this->kimlikNo . '</KimlikNo>
                            <Ad>' . $this->ad . '</Ad>
                            <Soyad>' . $this->soyad . '</Soyad>
                            <DogumGun>' . $this->dogumGun . '</DogumGun>
                            <DogumAy>' . $this->dogumAy . '</DogumAy>
                            <DogumYil>' . $this->dogumYil . '</DogumYil>
                        </YabanciKimlikNoDogrula>
                    </soap12:Body>
                </soap12:Envelope>';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $toSend);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'POST /Service/KPSPublic.asmx HTTP/1.1',
            'Host: tckimlik.nvi.gov.tr',
            'Content-Type: text/xml; charset=utf-8',
            'SOAPAction: "http://tckimlik.nvi.gov.tr/WS/YabanciKimlikNoDogrula"',
            'Content-Length: ' . strlen($toSend)
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        return strip_tags($response) == 'true';
    }
} 