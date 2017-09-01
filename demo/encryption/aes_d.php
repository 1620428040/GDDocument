<?php
//AES 算法和 CBC 模式  加密解密字符串
	$str='This is very important data     ';
    /* 打开加密算法和模式 */
    $td = mcrypt_module_open('rijndael-256', '', 'cbc', '');

    /* 创建初始向量，并且检测密钥长度。 
     * Windows 平台请使用 MCRYPT_RAND。 */
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);
    $ks = mcrypt_enc_get_key_size($td);

    /* 创建密钥 */
    $key = substr(md5('very secret key'), 0, $ks);

    /* 初始化加密 */
    mcrypt_generic_init($td, $key, $iv);

    /* 加密数据 */
    $encrypted = mcrypt_generic($td, $str);

    /* 结束加密，执行清理工作 */
    mcrypt_generic_deinit($td);

    /* 初始化解密模块 */
    mcrypt_generic_init($td, $key, $iv);

    /* 解密数据 */
    $decrypted = mdecrypt_generic($td, $encrypted);

    /* 结束解密，执行清理工作，并且关闭模块 */
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);

    /* 显示文本 */
    echo "==>".$str."<==";
	echo "<br/>";
    echo "==>".$encrypted."<==";
	echo "<br/>";
    echo "==>".$decrypted."<==";
?>