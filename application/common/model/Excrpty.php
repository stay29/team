<?php
namespace app\common\model;
use think\Model;
class Excrpty extends Model
    {
        const DS = DIRECTORY_SEPARATOR;
        const FILE = 1;
        const DIRECTORY = 2;
        const ALL = 3;

        /*用法*/
        //测试加密
  // $jiami  =new Excrpty();
  // $tmpFile = FILE_PATH.'/uploads/20191026/stay.rar';
  // $routh  = FILE_PATH.'/uploads/20191026/8488c84ced468b71808bf1180b3ff327';
  // $from =FILE_PATH.'/uploads/20191026/';
  // $b  = $jiami->en($tmpFile, $from,$slat = 'music');
  // var_dump($b);
  // dump($b);
  // // exit;
  // $a  = $jiami->de($routh, $from , $slat = 'music');
  // dump($a);
  // exit;
        /**
         * 文件加密
         *
         * @param        $from  源
         * @param        $to    保存位置
         * @param string $slat  密码
         *
         * @return array
         */
        public static function en($from , $to , $slat = 'xxx')
        {
            $data = [
                'sign' => 1 ,
                'msg'  => '' ,
            ];
            try
            {
                $imgObj = new \SplFileInfo($from);
                $stram = implode('' , [
                        '@@_____' ,
                        $slat ,
                        '@@_____' ,
                        $imgObj->getBasename() ,
                        '@@_____' ,
                    ]) . (file_get_contents($imgObj->getRealPath()));
                $stram = gzcompress($stram);
              $a=   file_put_contents(static::replaceToSysSeparator($to) . md5($imgObj->getRealPath()) , ($stram));
            $data = [
                'sign' => 1 ,
                'msg'  => '' ,
                'path' =>md5($imgObj->getRealPath()) ,
            ];
            } catch (\Exception $exception)
            {
                $data['sign'] = 0;
                $data['data'] = $exception->getMessage();
            }
            return $data;
        }

        /**
         * 解密
         *
         * @param        $from  源
         * @param        $to    保存位置
         * @param string $slat  密码
         *
         * @return array
         */
        public static function de($from , $to , $slat = 'xxx')
        {
            $data = [
                'sign' => 1 ,
                'msg'  => '' ,
            ];
            try
            {
                $imgObj = new \SplFileInfo($from);
                $stram = file_get_contents($imgObj->getRealPath());
                $stram = gzuncompress($stram);
                $reg = implode('' , [
                    '#' ,
                    '^@@_____' ,
                    preg_quote($slat , '#') ,
                    '@@_____' ,
                    '(.*?)' ,
                    '@@_____' ,
                    '#i' ,
                ]);

                preg_match($reg , $stram , $res);
                if(isset($res[1]))
                {
                    self::mkdir_($to);
                    $result = preg_replace($reg , '' , $stram);
                    file_put_contents(static::endDS($to) . $res[1] , $result);
                     $data = [
                       'sign' => 1 ,
                        'msg'  => '' ,
                       'path'=>$res[1]
                  ];
                }
                else
                {
                    $data['sign'] = 0;
                    $data['data'] = '解密出错';
                }
            } catch (\Exception $exception)
            {
                $data['sign'] = 0;
                $data['data'] = $exception->getMessage();
            }

            return $data;
        }

        /**
         * 自动为路径后面加DIRECTORY_SEPARATORY
         *
         * @param string $path 文件夹路径
         *
         * @return string
         */
        public static function endDS($path)
        {
            return rtrim(rtrim(static::replaceToSysSeparator($path) , '/') , '\\') . static::DS;
        }


        /**
         * @param $path
         *
         * @return string
         */
        public static function replaceToSysSeparator($path)
        {
            return strtr($path , [
                '\\' => static::DS ,
                '/'  => static::DS ,
            ]);
        }

        /**
         * @param $path
         *
         * @return string
         */
        public static function replaceToUrlSeparator($path)
        {
            return strtr($path , [
                '\\' => '/' ,
            ]);
        }

        /**
         * 格式化字节大小
         *
         * @param  number     $size 字节数
         * @param string |int $de
         *
         * @return string            格式化后的带单位的大小
         */
        public static function byteFormat($size , $de = 2)
        {
            $a = array(
                "B" ,
                "KB" ,
                "MB" ,
                "GB" ,
                "TB" ,
                "PB" ,
            );
            $pos = 0;
            while ($size >= 1024)
            {
                $size /= 1024;
                $pos++;
            }

            return round($size , $de) . " " . $a[$pos];
        }


        /**
         * 创建文件夹
         *
         * @param     $path
         * @param int $mode
         *
         * @return bool
         */
        public static function mkdir_($path , $mode = 0777)
        {
            return !is_dir(($path)) ? mkdir(($path) , $mode , 1) : @chmod($path , $mode);
        }


        /**
         * 列出文件夹里文件信息
         *
         * @param               $path
         * @param callable|null $callback
         * @param int           $flag
         *
         * @return array
         */
        public static function listDir($path , callable $callback = null , $flag = self::ALL)
        {
            $files = [];
            if(is_dir($path) && is_readable($path))
            {
                try
                {
                    $directory = new \FilesystemIterator ($path);

                    $filter = new \CallbackFilterIterator ($directory , function($current , $key , $iterator) use ($flag) {
                        switch ($flag)
                        {
                            case static::FILE:
                                return $current->isFile();
                            case static::DIRECTORY:
                                return $current->isDir();
                            default:
                                return true;
                        }
                    });

                    foreach ($filter as $info)
                    {
                        if(is_callable($callback))
                        {
                            $files[] = call_user_func_array($callback , [
                                $info ,
                            ]);
                        }
                        else
                        {
                            $files[] = $info;
                        }
                    }

                } catch (\Exception $e)
                {
                    $files = [];
                }
            }

            return $files;
        }

    }
