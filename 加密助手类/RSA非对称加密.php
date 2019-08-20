<?php
//私匙
$private_key = '-----BEGIN RSA PRIVATE KEY-----  
MIICXQIBAAKBgQC3//sR2tXw0wrC2DySx8vNGlqt3Y7ldU9+LBLI6e1KS5lfc5jl  
TGF7KBTSkCHBM3ouEHWqp1ZJ85iJe59aF5gIB2klBd6h4wrbbHA2XE1sq21ykja/  
Gqx7/IRia3zQfxGv/qEkyGOx+XALVoOlZqDwh76o2n1vP1D+tD3amHsK7QIDAQAB  
AoGBAKH14bMitESqD4PYwODWmy7rrrvyFPEnJJTECLjvKB7IkrVxVDkp1XiJnGKH  
2h5syHQ5qslPSGYJ1M/XkDnGINwaLVHVD3BoKKgKg1bZn7ao5pXT+herqxaVwWs6  
ga63yVSIC8jcODxiuvxJnUMQRLaqoF6aUb/2VWc2T5MDmxLhAkEA3pwGpvXgLiWL  
3h7QLYZLrLrbFRuRN4CYl4UYaAKokkAvZly04Glle8ycgOc2DzL4eiL4l/+x/gaq  
deJU/cHLRQJBANOZY0mEoVkwhU4bScSdnfM6usQowYBEwHYYh/OTv1a3SqcCE1f+  
qbAclCqeNiHajCcDmgYJ53LfIgyv0wCS54kCQAXaPkaHclRkQlAdqUV5IWYyJ25f  
oiq+Y8SgCCs73qixrU1YpJy9yKA/meG9smsl4Oh9IOIGI+zUygh9YdSmEq0CQQC2  
4G3IP2G3lNDRdZIm5NZ7PfnmyRabxk/UgVUWdk47IwTZHFkdhxKfC8QepUhBsAHL  
QjifGXY4eJKUBm3FpDGJAkAFwUxYssiJjvrHwnHFbg0rFkvvY63OSmnRxiL4X6EY  
yI9lblCsyfpl25l7l5zmJrAHn45zAiOoBrWqpM5edu7c  
-----END RSA PRIVATE KEY-----';

//公匙
$public_key = '-----BEGIN PUBLIC KEY-----  
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC3//sR2tXw0wrC2DySx8vNGlqt  
3Y7ldU9+LBLI6e1KS5lfc5jlTGF7KBTSkCHBM3ouEHWqp1ZJ85iJe59aF5gIB2kl  
Bd6h4wrbbHA2XE1sq21ykja/Gqx7/IRia3zQfxGv/qEkyGOx+XALVoOlZqDwh76o  
2n1vP1D+tD3amHsK7QIDAQAB  
-----END PUBLIC KEY-----';

// 这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
$pi_key =  openssl_pkey_get_private($private_key);

// 这个函数可用来判断公钥是否是可用的，可用返回资源id Resource id
$pu_key = openssl_pkey_get_public($public_key);

echo "私钥";print_r($pi_key);echo "\n\n";
echo "公钥";print_r($pu_key);echo "\n\n";

// 原始数据
$data = "账号:admin，密码:123456";

// 加密以后的数据，用于在网络上传输
$encrypted = "";

// 解密后的数据
$decrypted = "";

echo "原始数据为:".$data."\n\n";
echo "通过私钥加密: \n\n";
//私钥加密
if(openssl_private_encrypt($data,$encrypted,$pi_key))
{

    $encrypted = base64_encode($encrypted);//加密后的内容通常含有特殊字符，需要编码转换下，否则乱码，在网络间通过url传输时要注意base64编码是否是url安全的
    echo "加密成功，加密后数据为:".$encrypted."\n\n";
}else
{
    die('加密失败');
}

echo "通过公钥解密:\n\n";
//私钥加密的内容通过公钥可用解密出来
if(openssl_public_decrypt(base64_decode($encrypted),$decrypted,$pu_key))
{
    echo "解密成功，解密后数据为:".$decrypted."\n\n";
}else
{
    die('解密失败');
}


echo "---------------------------------------\n\n";
echo "公钥加密:\n\n";
openssl_public_encrypt($data,$encrypted,$pu_key);//公钥加密
$encrypted = base64_encode($encrypted);
echo "公钥加密后数据：".$encrypted."\n\n";

echo "私钥解密：\n\n";
openssl_private_decrypt(base64_decode($encrypted),$decrypted,$pi_key);//私钥解密
echo "私钥解密后数据：".$decrypted."\n\n";
