<?php
/**
 * Created by PhpStorm.
 * User: MrCong
 * Date: 2017/2/12
 * Time: 21:15
 */

namespace Persimmon\Storage;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Models\Attachments;
use Qiniu\Etag;
use zgldh\QiniuStorage\QiniuStorage;

class QiniuUploads
{

    protected $client_ip = '127.0.0.1';

    public function uploads(Request $request)
    {

        $file = $request->file('file');
        $this->client_ip = $request->ip();

        if (!$file->isValid()) {
            return ['status' => '400'];
        }

        $fileObject = $this->fileExists($file);
        if (isset($fileObject['path'])) {
            $path = cdn($fileObject['path']);
            return ['status' => '200', 'filename' => $path, 'url' => $path];
        }

        $fileObject = $this->put($file);
        return ['status' => '200', 'filename' => $fileObject, 'url' => $fileObject];
    }

    public function remoteAllFiles($path = '/')
    {
        $disk = QiniuStorage::disk('qiniu');
        $files = $disk->allFiles($path);
        return $files;
    }

    public function localFiles(Request $request)
    {
        $rows = intval($request->rows) > 0 ? $request->rows : 20;
        $list = Attachments::paginate($rows);
        foreach ($list as $key => $item) {
            $list[$key]['path'] = cdn($item['path']);
        }
        return $list;
    }

    public function cacheGravatar($email)
    {
        $gravatar = sprintf("https://cn.gravatar.com/avatar/%s?d=identicon&s=60", md5(strtolower(trim($email))));
        $disk = QiniuStorage::disk('qiniu');
        $fileName = 'avatar_' . date('Y-m-d');
        $disk->fetch($gravatar, $fileName);
        $download = $disk->downloadUrl($fileName, config('filesystems.disks.qiniu.protocol'));
        return $download->getUrl();
    }

    public function put($file)
    {
        $disk = QiniuStorage::disk('qiniu');
        $ext = $file->extension();
        $realPath = $file->getRealPath();
        $key = Etag::sum($realPath);
        $fileName = sprintf("%s.%s", $key[0], $ext);
        $contents = @file_get_contents($realPath);
        $result = $disk->put($fileName, $contents);
        if ($result) {
            $hash1 = sha1_file($file->getRealPath());
            $md5 = md5_file($file->getRealPath());
            $this->saveFileUrl($fileName, $hash1, $md5);
        }
        $download = $disk->downloadUrl($fileName, config('filesystems.disks.qiniu.protocol'));
        return $download->getUrl();
    }

    public function putForContent($base64_content)
    {
        $dir = './cache/';
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $disk = QiniuStorage::disk('qiniu');
        $realPath = $dir . md5(time());
        @file_put_contents($realPath, $base64_content->scalar);

        $fileObject = $this->fileExists($realPath);
        if (isset($fileObject['path'])) {
            unlink($realPath);
            return cdn($fileObject['path']);
        }

        $key = Etag::sum($realPath);
        $fileName = sprintf("%s.%s", $key[0], $this->getExtension($realPath));
        $contents = @file_get_contents($realPath);
        $result = $disk->put($fileName, $contents);
        if ($result) {
            $hash1 = sha1_file($realPath);
            $md5 = md5_file($realPath);
            $this->saveFileUrl($fileName, $hash1, $md5);
        }
        unlink($realPath);
        $download = $disk->downloadUrl($fileName, config('filesystems.disks.qiniu.protocol'));
        return $download->getUrl();
    }

    private function getExtension($realPath)
    {
        $info = getimagesize($realPath);
        $mime = explode('/', $info['mime']);
        $ext = end($mime);
        return $ext;
    }

    private function saveFileUrl($path, $hash1, $md5)
    {
        $attachment = new Attachments;
        $attachment->path = $path;
        $attachment->hash1 = $hash1;
        $attachment->user_id = Auth::id();
        $attachment->md5 = $md5;
        $attachment->ipaddress = $this->client_ip;
        return $attachment->save();
    }

    private function fileExists($file)
    {
        $realPath = is_string($file) ? $file : $file->getRealPath();
        $hash1 = sha1_file($realPath);
        $data = Attachments::where('hash1', $hash1)->first();
        return $data;
    }

}