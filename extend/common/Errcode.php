<?php


namespace common;


class Errcode
{
    /**
     * 错误码按业务分类添加
     *
     * 1xxx 系统提示    eg:  签名错误、xx参数必须等
     * 2xxx 用户相关业务提示  eg: 密码错误、 用户等级不够、用户未认证等
     */
    const SUCCESS = 0;//成功
    const SYS_ERROR = 1000;//系统错误
    const SYS_VERSION_ERROR = 1001;//版本信息错误
    const SYS_TOKEN_ERROR = 1002;//token验证失败
    const SYS_PARAM_ERR = 1003;  //参数错误
    const SYS_ERRMSG_UNDEFINE = 1004;  //未定义消息体
    const SYS_EXCEPTION = 1005;   //异常捕获
    const SYS_FALSY = 1006;     //falsy值
    const SYS_SQL_AFFECTNO = 1007; //sql 执行返回falsy
    const SYS_HXAPI_ERR = 1008;       //环信APIERROR
    const SYS_JSON = 1009;
    const SYS_SIGN_EMPTY = 1010;//签名必须
    const SYS_SIGN_FAIL = 1011;//签名错误
    const SYS_MOBILE_EMPTY = 1012;//手机号码必须
    const SYS_MOBILE_FORMAT = 1013;//手机号格式错误
    const SYS_PASSWORD_EMPTY = 1014;//密码必须
    const SYS_NICKNAME_EMPTY = 1015;//昵称必须
    const SYS_AGE_EMPTY = 1016; //年龄必须
    const SYS_OCCUP_EMPTY = 1017;//职业必须
    const SYS_EDU_EMPTY = 1018;//学历必须
    const SYS_INCOME_EMPTY = 1019;//收入必须
    const SYS_HEIGHT_EMPTY = 1020;//身高必须
    const SYS_MARITAL_EMPTY = 1021;//婚姻状况必须
    const SYS_SEX_EMPTY = 1022;//性别必须
    const SYS_ACCOUNT_EMPTY = 1023;//账号必须
    const SYS_ACCOUNT_FORMAT = 1024;//账号格式错误
    const SYS_UPLOAD_FILE_EMPTY = 1025;//上传文件空
    const SYS_UPLOAD_FILE_ERR = 1026;//上传文件错误
    const SYS_UPLOAD_FILE_FAIL = 1027;//上传文件失败
    const SYS_SMS_CODE_EMPTY = 1028;//短信验证码为空
    const SYS_SMS_SEND_ERROR = 1029;//短信发送失败
    const SYS_SMS_SEND_MAX_TIME = 1030;//短信超过最大次数
    const SYS_SMS_SEND_ONE_MINUTE = 1031;//短信每分钟多次发送
    const SYS_SMS_NOT_VALID = 1032;//短信验证码已失效
    const SYS_SMS_ERROR = 1033;//短信验证码已失效
    const SYS_ID_NUMBER_EMPTY = 1034;//身份证号码为空
    const SYS_ID_NUMBER_ERROR = 1035;//身份证号码校验错误
    const SYS_TRUE_NAME_EMPTY = 1036;//真实姓名为空
    const SYS_MOBILE_HAS_BIND = 1037;//手机号码已经绑定
    const SYS_NICKNAME_FORMAT= 1038;//昵称格式错误
    const SYS_QQ_FORMAT = 1039;//qq号格式错误
    const SYS_WEIXIN_FORMAT = 1040;//微信号格式错误
    const SYS_HEIGHT_FORMAT = 1041;//身高格式错误
    const SYS_DATA_FORMAT = 1042;//数据格式错误
    const SYS_COIN_FORMAT = 1043;//钻石格式错误
    const SYS_ERR_TIME_SELECTED = 1044;//开始时间不能大于结束时间
    const SYS_EXAMINE_STATUS_REFUSE  = 1045;//审核未通过
    const SYS_PASSWORD_FORMAT = 1054;//密码格式错误

    protected static $err_code_msg = array(
        self::SUCCESS => '',
        self::SYS_ERROR => '系统错误',
        self::SYS_VERSION_ERROR => '版本信息错误',
        self::SYS_TOKEN_ERROR => 'token验证失败',
        self::SYS_PARAM_ERR => '参数错误',
        self::SYS_ERRMSG_UNDEFINE => '未定义消息类型',
        self::SYS_EXCEPTION => '异常捕获',
        self::SYS_FALSY  => 'callback return fasly',
        self::SYS_SQL_AFFECTNO => '无数据生效',
        self::SYS_SIGN_EMPTY => '签名必须',
        self::SYS_SIGN_FAIL => '签名错误',
        self::SYS_MOBILE_EMPTY => '手机号必须',
        self::SYS_MOBILE_FORMAT => '手机号格式错误',
        self::SYS_PASSWORD_EMPTY => '密码必须',
        self::SYS_NICKNAME_EMPTY => '昵称必须',
        self::SYS_AGE_EMPTY => '年龄必须',
        self::SYS_OCCUP_EMPTY => '职业必须',
        self::SYS_EDU_EMPTY => '学历必须',
        self::SYS_INCOME_EMPTY => '收入必须',
        self::SYS_HEIGHT_EMPTY => '身高必须',
        self::SYS_MARITAL_EMPTY => '婚姻状况必须',
        self::SYS_SEX_EMPTY => '性别必须',
        self::SYS_ACCOUNT_EMPTY => '账号必须',
        self::SYS_UPLOAD_FILE_EMPTY => '上传文件空',
        self::SYS_UPLOAD_FILE_ERR => '上传文件错误',
        self::SYS_UPLOAD_FILE_FAIL => '上传文件失败',
        self::SYS_SMS_CODE_EMPTY => '短信验证码为空',
        self::SYS_SMS_SEND_ERROR => '短信验证码发送失败',
        self::SYS_SMS_SEND_MAX_TIME => '今日接收短讯已经到达最大次数，请明天再试吧',
        self::SYS_SMS_SEND_ONE_MINUTE => '验证码1分钟只能请求一次，请勿多次发送',
        self::SYS_SMS_NOT_VALID => '短信验证码已失效',
        self::SYS_SMS_ERROR => '短信验证码错误',
        self::SYS_ID_NUMBER_EMPTY => '身份证号码必须',
        self::SYS_ID_NUMBER_ERROR => '身份证号码校验错误',
        self::SYS_TRUE_NAME_EMPTY => '真实姓名必须',
        self::SYS_MOBILE_HAS_BIND => '手机号码已绑定过了',
        self::SYS_NICKNAME_FORMAT => '昵称格式错误',
        self::SYS_QQ_FORMAT => 'qq格式错误',
        self::SYS_WEIXIN_FORMAT => '微信号格式错误',
        self::SYS_HEIGHT_FORMAT => '身高格式错误',
        self::SYS_DATA_FORMAT => '数据格式错误',
        self::SYS_COIN_FORMAT => '钻石格式错误',
        self::SYS_ERR_TIME_SELECTED => '开始时间不能大于结束时间',
        self::SYS_EXAMINE_STATUS_REFUSE => '审核未通过',
        self::SYS_PASSWORD_FORMAT => '密码格式错误',

    );

    /**
     * 返回错误中文消息
     * @param $code
     * @return mixed|string
     */
    public static function get_err_msg($code)
    {
        return isset(self::$err_code_msg[$code]) ? self::$err_code_msg[$code] : '';
    }
}