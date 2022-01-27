<?php

namespace App\Models;

use App\Exceptions\FileExtException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CrmFile extends Model
{
    use HasFactory;

    /**
     * @var mixed
     */
    private $uuid;
    /**
     * @var mixed
     */
    private $user_id;
    /**
     * @var bool|mixed
     */
    private $publication;
    /**
     * @var mixed
     */
    private $file_original_name;
    /**
     * @var mixed|string
     */
    private $file_type;
    /**
     * @var mixed
     */
    private $extension;
    /**
     * @var mixed|string
     */
    private $file_source;
    /**
     * @var mixed|string
     */
    private $file_share;

    protected $fillable = [
      'uuid',
      'user_id',
      'publication',
      'file_original_name',
      'size',
      'file_type',
      'extension',
      'file_source',
      'file_share'
    ];
    public const IMAGE_EXT = ['JPG', 'jpg', 'jpeg', 'png', 'gif', 'tiff'];

    public const DOCUMENT_EXT = [
        'doc',
        'docx',
        'dot',
        'pdf',
        'odt',
        'xlt',
        'dwg',
        'xls',
        'xml',
        'xlsx',
        'xlsm',
        'xltm',
        'txt',
        'ods',
        'docm',
        'dotx',
        'dotm',
        'wpd',
        'wps',
        'csv',
        'ppt',
        'pps',
        'pot',
        'pptx',
        'pptm',
        'potx',
        'potm',
        'sxw',
        'stw',
        'sxc',
        'stc',
        'xlw',
        'cdr',
        'eps',
        'tif',
        'xsd',
        'dwg',
        'ai',
        'tiff',
        'cdr',
        'eps',
        'ai',
        'tif',
        'psd',
        'svg',
        //Archives
        '7z',
        'zip',
        'ace',
        'arj',
        'cab',
        'cbr',
        'gz',
        'gzip',
        'pkg',
        'sit',
        'spl',
        'tar',
        'tar-gz',
        'tgz',
        'xar',
        'zipx',
        'rar',
        'rpm'

    ];

    const IMAGE_MIMES = [ 'image/jpeg', 'image/png', 'image/gif', 'image/tiff' ];
    const DOCUMENT_MIMES  = [
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/pdf',
        'image/tiff',
        'image/tif',
        'image/tiff-fx',
        'application/postscript',
        'image/vnd.adobe.photoshop',
        'image/vnd.dwg',
        'application/vnd.oasis.opendocument.text',
        'application/vnd.ms-excel',
        'application/xml',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-excel.sheet.macroenabled.12',
        'application/vnd.ms-excel.template.macroenabled.12',
        'text/plain',
        'application/vnd.oasis.opendocument.spreadsheet',
        'application/vnd.ms-word.document.macroenabled.12',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'application/vnd.ms-word.template.macroenabled.12',
        'application/vnd.wordperfect',
        'application/vnd.ms-works',
        'text/csv',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.ms-powerpoint.presentation.macroenabled.12',
        'application/vnd.openxmlformats-officedocument.presentationml.template',
        'application/vnd.ms-powerpoint.template.macroenabled.12',
        'application/vnd.sun.xml.writer',
        'application/vnd.sun.xml.writer.template',
        'application/vnd.sun.xml.calc',
        'application/zip',
        'application/x-rar-compressed',
    ];

    const PROJECT_DOCUMENT_EXT      = ['doc', 'docx', 'pdf', 'odt', 'zip'];
    const PROJECT_AUDIO_EXT         = ['mp3', 'ogg', 'mpga'];
    const PROJECT_VIDEO_EXT         = ['mp4', 'mpeg'];
    const TYPE_IMAGE                = 'image';
    /**
     * @param string $ext
     * @return string
     * @throws FileExtException
     */
    public function getType($file): ?string
    {
        $ext = mb_strtolower($file->getClientOriginalExtension());

        if (in_array($ext, CrmFile::IMAGE_EXT)) {
            return 'image';
        }

        if (in_array($ext, CrmFile::PROJECT_AUDIO_EXT)) {
            return 'audio';
        }

        if (in_array($ext, CrmFile::PROJECT_VIDEO_EXT)) {
            return 'video';
        }

        if (in_array($ext, CrmFile::PROJECT_DOCUMENT_EXT)) {
            return 'document';
        }

        if (in_array($ext, CrmFile::DOCUMENT_EXT)) {
            return 'file';
        }
        return null;
    }
    public function user(): HasOne
    {
        return $this->hasOne(CrmUser::class, 'id', 'user_id');
    }
}
