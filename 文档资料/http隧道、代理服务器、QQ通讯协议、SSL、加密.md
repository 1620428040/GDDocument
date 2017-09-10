[QQ通信协议](http://blog.csdn.net/realxie/article/details/7270119)
[什么是HTTP隧道，怎么理解HTTP隧道呢？](https://www.zhihu.com/question/21955083)
[免费代理服务器每小时更新](http://31f.cn/)
[(腾讯)免费版DVSSL证书](https://console.cloud.tencent.com/ssl?apply=1&fromSource=ssl)
[openssl基本原理 + 生成证书 + 使用实例](http://blog.csdn.net/oldmtn/article/details/52208747)


### 非对称加密
对称加密算法在加密和解密时使用的是同一个秘钥；而非对称加密算法需要两个密钥来进行加密和解密，这两个秘钥是公开密钥（public key，简称公钥）和私有密钥（private key，简称私钥）


### 加密解密、签名和验证签名
公钥加密数据,然后私钥解密的情况被称为加密解密,私钥加密数据,公钥解密一般被称为签名和验证签名。
签名主要是用来验证身份


### 常用算法
RSA 加/解密,签名验签
DSA 签名
SHA 摘要算法
MD5 摘要算法


### CA机构，又称为证书授权（CertificateAuthority）中心
确认公钥没有被人替换的公正方，找CA是要花钱的
也可以自己做自签名的证书


### 秘钥文件格式
PEM     base64编码
DER     二进制
X509    通用的证书文件格式定义
PKCS    指定的存放密钥的文件标准

cer     二进制编码证书、公钥(DER使用定长模式，而CER使用变长模式)
p12     二进制编码秘钥(PKCS#12：个人信息交换语法标准；文件可作为Java中的秘钥库或信任度直接使用)


### 例如-在iOS开发时创建证书和秘钥